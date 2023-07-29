<?php


    namespace App\Controller\api\v1\admin;
    
    use App\Repository\RolesRepository;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    
    use App\Utils\Globals;
    use App\Utils\ManifestHttp;
    use App\Utils\SECURITY_UTILS;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;


    /**
     * Class AdminController
     * @package App\Controller\api\v1\admin
     * @Route("/api/v1/admin", name="api_ctrl_admin_")
     * 
     */
    class AdminController extends AbstractController
    {
        private Globals $globals;
        private RolesRepository $rolesRepository;

        public function __construct(
            Globals $globals, 
            RolesRepository $rolesRepository, 
        )
        {
            $this->globals = $globals;
            $this->rolesRepository = $rolesRepository;
        }

        /**
         * @Route("/addRole", name="addRole", methods={"POST"})
         * @return Response
         */
        public function addRole() : Response
        {
            $data = $this->globals->jsondecode();
            if(!isset(
                $data->rolesEmp, 
            )) return $this->globals->error(ManifestHttp::FROM_ERROR);

            if (SECURITY_UTILS::AnalyseDonne(trim($data->rolesEmp))) {
                $role = $this->rolesRepository->findOneBy(["rolesEmp" => trim($data->rolesEmp)]);
                if ($role) return $this->globals->error(ManifestHttp::ROLES_USED);

                $this->rolesRepository->save(trim($data->rolesEmp));
                return $this->json(['message' => 'Roles added successfully']);
            }
            return $this->globals->error(ManifestHttp::FROM_ERROR);
        }

        /**
         * Get a list of all users.
         *
         * @Route("/listeRole", name="listeRole", methods={"GET"})
         */
        public function listeRole(): JsonResponse
        {
            // Récupérer tous les roles
            $users = $this->rolesRepository->findAll();

            // Retourner la réponse JSON avec la liste des utilisateurs
            return $this->json($users);
        }

        /**
         * Get information of a specific user.
         *
         * @Route("/searche", name="searche", methods={"POST"})
         */
        public function searche(Request $request): JsonResponse
        {
            // Récupérer l'ID de l'utilisateur à partir des données de la requête
            $id = $request->query->get('id');

            // Récupérer l'utilisateur correspondant à l'ID spécifié
            $user = $this->rolesRepository->find($id);

            // Vérifier si l'utilisateur existe
            if (!$user) {
                // Retourner une réponse JSON avec un message d'erreur approprié
                return $this->json(['message' => 'Roles not found'], Response::HTTP_NOT_FOUND);
            }

            // Retourner la réponse JSON avec les informations de l'utilisateur
            return $this->globals->success([
                'User' => $user->ListeRoles()
            ]);
        }

        /**
         * Update an existing user.
         *
         * @Route("/update", name="update", methods={"PUT", "PATCH"})
         */
        public function update(Request $request): JsonResponse
        {
            // Récupérer l'ID de l'utilisateur à partir des données de la requête
            $id = $request->query->get('id');

            // Récupérer l'utilisateur correspondant à l'ID spécifié
            $role = $this->rolesRepository->find($id);
            
            // Vérifier si l'utilisateur existe
            if (!$role) {
                // Retourner une réponse JSON avec un message d'erreur approprié
                return $this->json(['message' => 'Roles not found'], Response::HTTP_NOT_FOUND);
            }

            // Récupérer les données de la requête
            $data = json_decode($request->getContent(), true);

            // Enregistrer les modifications dans la base de données
            $this->rolesRepository->update($$data->email);

            // Retourner la réponse JSON avec les informations de l'utilisateur mis à jour
            return $this->json($role);
        }


        /**
         * Delete a specific user.
         *
         * @Route("/delete", name="delete", methods={"DELETE"})
         */
        public function delete(Request $request): JsonResponse
        {
            // Récupérer l'ID de l'utilisateur à partir des données de la requête
            $id = $request->query->get('id');

            // Récupérer l'utilisateur correspondant à l'ID spécifié
            $role = $this->rolesRepository->find($id);

            // Vérifier si l'utilisateur existe
            if (!$role) {
                // Retourner une réponse JSON avec un message d'erreur approprié
                return $this->json(['message' => 'Roles not found'], Response::HTTP_NOT_FOUND);
            }

            // Supprimer l'utilisateur de la base de données
            $this->rolesRepository->delete($role);

            return $this->json(['message' => 'Roles deleted successfully']);

        }
    }
    

?>