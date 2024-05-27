<?php
namespace App\Services;

use App\Models\Organization;
use Exception;
use Ramsey\Uuid\Uuid;

class OrganizationService
{
    public function createOrganization(array $data)
    {
        try {
            $data['uuid'] = Uuid::uuid4()->toString();
            $organization = Organization::create($data);

            return [
                'success' => true,
                'organization' => $organization
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create organization: ' . $e->getMessage()
            ];
        }
    }

    public function updateOrganization(Organization $organization, array $data){
        try {
            $organization->name = $data['name'];
            $organization->save();

            return [
                'success' => true,
                'organization' => $organization
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update organization: ' . $e->getMessage()
            ];
        }
    }

    public function deleteOrganization($uuid)
    {
        $organization = Organization::where('uuid', $uuid)->first();

        if (!$organization) {
            return [
                'success' => false,
                'message' => 'Organization not found'
            ];
        }

        try {
            $organization->delete();
            return [
                'success' => true,
                'message' => 'Organization deleted successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete organization: ' . $e->getMessage()
            ];
        }
    }

    public function organizationExist(string $uuid){
        try {
            $organization = Organization::findOrFail($uuid);
            return [
                'success' => true,
            ];
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return [
                'success' => false,
                'message' => 'Organization not found',
                'error' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function getAllOrganizations(){
        return Organization::all();
    }
}