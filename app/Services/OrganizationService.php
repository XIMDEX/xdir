<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Exception;


class OrganizationService
{
    protected $uuidService;

    /**
     * @param UuidService $uuidService
     */
    public function __construct(UuidService $uuidService, )
    {
        $this->uuidService = $uuidService;
    }

    /**
     * Creates a new organization with the given data.
     *
     * @param array $data The data for the organization to create.
     * @throws Exception If there is any issue during creation.
     * @return array An array containing the success status and the organization data or error message.
     */
    public function createOrganization(array $data)
    {
        try {
            $data['uuid'] = $this->uuidService->generateUuid();
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

    /**
     * Updates the specified organization with the given data.
     *
     * @param Organization $organization The organization to update.
     * @param array $data The data to update the organization with.
     * @throws Exception If there is any issue during the update.
     * @return array An array containing the success status and updated organization data or error message.
     */
    public function updateOrganization(Organization $organization, array $data)
    {
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

    /**
     * Deletes the organization with the given UUID.
     *
     * @param string $uuid The UUID of the organization to delete.
     * @throws Exception If there is any issue during deletion.
     * @return array An array containing the success status and a message.
     */

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
    /**
     * Checks if an organization exists with the given UUID.
     *
     * @param string $uuid The UUID of the organization to check.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no organization is found.
     * @throws \Exception If an unexpected error occurs.
     * @return array An array containing the success status and optionally an error message.
     */

    public function organizationExist(string $uuid)
    {
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

    /**
     * Retrieves all organizations.
     *
     * @return \Illuminate\Database\Eloquent\Collection Returns a collection of all organizations.
     */
    public function getAllOrganizations()
    {
        return Organization::all();
    }

    /**
     * Adds a user to an organization.
     *
     * @param string $organizationUuid The UUID of the organization to add the user to.
     * @param string $userUuid The UUID of the user to add to the organization.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the organization or user does not exist.
     * @throws \Exception If an unexpected error occurs.
     * @return array An array containing the success status and optionally an error message.
     */
    public function addUserToOrganization(string $organizationUuid, string $userUuid)
    {
        try {
            $organization = Organization::findOrFail($organizationUuid);
            $user = User::findOrFail($userUuid);
            $organization->users()->syncWithoutDetaching($user);
            return [
                'status' => Response::HTTP_OK,
                'message' => 'User added to organization successfully'
            ];
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return [
                'status' => Response::HTTP_NOT_FOUND,
                'message' => 'Organization or user not found',
                'error' => $e->getMessage()
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'An unexpected error occurred',
                'error' => $e->getMessage()
            ];
        }
    }
}
