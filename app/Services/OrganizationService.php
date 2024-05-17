<?php
namespace App\Services;

use App\Models\Organization;
use Exception;
use Ramsey\Uuid\Uuid;

class OrganizationService
{
    public function createOrganization(array $data)
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        $organization = Organization::create($data);

        return [
            'success' => true,
            'organization' => $organization
        ];
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
}