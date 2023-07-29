<?php


    namespace App\Controller\api\v1\raport;
    
    //use Symfony\Component\Security\Core\Annotation\Security;

    use App\Entity\User;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    
    use App\Repository\UserRepository;
    use App\Service\EmailService;
    use App\Utils\Globals;
    use App\Utils\ManifestHttp;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    //Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")

    /**
     * Class FileRaportController
     * @package App\Controller\api\v1\raport
     * @Route("/api/v1/raport", name="api_ctrl_login_")
     * 
     */
    class FileRaportController extends AbstractController
    {
        private Globals $globals;
        private UserRepository $userRepository;
        private EmailService $EmailS;

        public function __construct(Globals $globals, UserRepository $userRepository, EmailService $EmailS)
        {
            $this->globals = $globals;
            $this->userRepository = $userRepository;
            $this->EmailS = $EmailS;
        }

        /**
         * Get a list of all users.
         *
         * @Route("/listeUser", name="listeUser", methods={"GET"})
         */
        public function listeUser(): JsonResponse
        {
            // Récupérer tous les utilisateurs
            $users = $this->userRepository->findAll();

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
            $user = $this->userRepository->find($id);

            // Vérifier si l'utilisateur existe
            if (!$user) {
                // Retourner une réponse JSON avec un message d'erreur approprié
                return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            // Retourner la réponse JSON avec les informations de l'utilisateur
            return $this->globals->success([
                'User' => $user->ListeUser()
            ]);
        }

        /**
         * Update an existing user.
         *
         * @Route("/update", methods={"PUT", "PATCH"})
         */
        public function update(Request $request): JsonResponse
        {
            // Récupérer l'ID de l'utilisateur à partir des données de la requête
            $id = $request->query->get('id');

            // Récupérer l'utilisateur correspondant à l'ID spécifié
            $user = $this->userRepository->find($id);
            
            // Vérifier si l'utilisateur existe
            if (!$user) {
                // Retourner une réponse JSON avec un message d'erreur approprié
                return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            // Récupérer les données de la requête
            $data = json_decode($request->getContent(), true);

            // Enregistrer les modifications dans la base de données
            $this->userRepository->update($$data->email);

            // Retourner la réponse JSON avec les informations de l'utilisateur mis à jour
            return $this->json($user);
        }


        /**
         * Delete a specific user.
         *
         * @Route("/delete", methods={"DELETE"})
         */
        public function delete(Request $request): JsonResponse
        {
            // Récupérer l'ID de l'utilisateur à partir des données de la requête
            $id = $request->query->get('id');

            // Récupérer l'utilisateur correspondant à l'ID spécifié
            $user = $this->userRepository->find($id);

            // Vérifier si l'utilisateur existe
            if (!$user) {
                // Retourner une réponse JSON avec un message d'erreur approprié
                return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            // Supprimer l'utilisateur de la base de données
            $this->userRepository->delete($user);

            return $this->json(['message' => 'Users deleted successfully']);

        }



        
    }
    

?>