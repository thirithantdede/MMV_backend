<?php

namespace App\Services\Publish;

use App\Models\Project;
use App\Services\BuildingFootPrint\BuildingFootPrintService;
use App\Services\Element\ElementService;
use App\Services\Floor\FloorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PublishService
{
    /**
     * Retrieve public project data with related resources
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getData(Request $request): array
    {
        try {

            $project = Project::where('uri', $request->uri)
                ->where('is_public', true)
                ->firstOrFail();

            return [
                'status' => 'success',
                'data' => [
                    'project' => $project,
                    'floors' => (new FloorService)->getFloors($project),
                    'building_footprint' => (new BuildingFootPrintService)->getBuildingFootPrint($project),
                    'elements' => (new ElementService)->getElements($project),
                ],
            ];
        } catch (ValidationException $e) {
            Log::warning('Invalid getData request', [
                'uri' => $request->uri,
                'errors' => $e->errors(),
            ]);

            throw $e;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve project data', [
                'uri' => $request->uri,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Failed to retrieve project data', 500);
        }
    }

    public function getProjectLists(Request $request): array
    {
        $projects = Project::
        with("user")
        ->withCount('elements')
        ->where("is_public", true)
        ->get()->toArray();

        return $projects;
    }

    /**
     * Publish or update a project with validation and version control
     *
     * @param Request $request
     * @return Project
     * @throws ValidationException
     */
    public function publish(Request $request): Project
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[\p{L}\s-]+$/u',
            'description' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:1000',
            'uri' => 'required|string|max:255|unique:projects,uri,' . auth()->user()->project?->id,
            'is_public' => 'required|string|in:true,false',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($request) {
            try {
                $project = auth()->user()->project ?? new Project(['user_id' => auth()->id()]);

                $project->fill([
                    'name' => trim($request->name),
                    'description' => $request->description,
                    'uri' => Str::slug($request->uri),
                    'address' => $request->address,
                    'is_public' => $request->boolean('is_public'),
                ]);

                // Handle photo upload
                if ($request->hasFile('photo')) {
                    // Delete old photo if exists
                    if ($project->photo) {
                        Storage::disk('public')->delete("project/cover/{$project->photo}");
                    }

                    $photo = $request->file('photo');
                    $extension = $photo->getClientOriginalExtension();
                    $filename = Str::slug($project->name) . '_' . time() . '.' . $extension;
                    $photo->storeAs('project/cover', $filename, 'public');
                    $project->photo = $filename;
                }

                // Increment version
                $project->current_version = $this->incrementPatchVersion($project->current_version);

                $project->published_at = now();
                $project->save();

                Log::info('Project published successfully', [
                    'project_id' => $project->id,
                    'uri' => $project->uri,
                    'user_id' => auth()->id(),
                ]);

                return $project;
            } catch (\Exception $e) {
                Log::error('Project publication failed', [
                    'error' => $e->getMessage(),
                    'user_id' => auth()->id(),
                ]);

                throw new \Exception($e->getMessage(), 500);
            }
        });
    }

    /**
     * Increment the patch version of a semantic version string
     *
     * @param string|null $version
     * @return string
     */
    private function incrementPatchVersion(?string $version): string
    {
        if (!$version || !preg_match('/^\d+\.\d+\.\d+$/', $version)) {
            return '1.0.0';
        }

        $parts = explode('.', $version);
        $parts[2] = (int)$parts[2] + 1;

        return implode('.', $parts);
    }
}