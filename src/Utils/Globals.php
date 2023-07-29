<?php

   namespace App\Utils;
   
    use Ramsey\Uuid\Uuid;
    use Doctrine\Persistence\ManagerRegistry;
    use Doctrine\Persistence\ObjectManager;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mailer\Mailer;
    use Kreait\Firebase\Contract\Storage;


    class Globals
    {
        public ManagerRegistry $managerRegistry;
        public UserPasswordHasherInterface $encoder;
        private Storage $storage;


        public function __construct(
            ManagerRegistry $managerRegistry, 
            UserPasswordHasherInterface $encoder,
            Storage $storage
        )
        {
            $this->managerRegistry = $managerRegistry;
            $this->encoder = $encoder;
            $this->storage = $storage;
        }

        public function success(array $data = null , array $success =  ManifestHttp::SUCCESS) : JsonResponse
        {
            return new JsonResponse([
                "status" => $success["status"] ?? 1,
                "message" => $success["message"] ?? "Success",
                "data" => $data,
            ], $success["code"] ?? 200);
        }

        public function EmailAdmin()
        {
            return trim('admin@fylap.com');
        }

        public function TRANSPORT()
        {
            $transport = Transport::fromDsn($_ENV['MAILER_DSN']);
            return  new Mailer($transport);
        }


        public function error(array $error =  ManifestHttp::ERROR) : JsonResponse
        {
            return new JsonResponse([
                "status" => $error["status"] ?? 0,
                "message" => $error["message"] ?? "error",
            ], $error["code"] ?? 500);
        }

        public function errorNoFound(array $data = null, array $error =  ManifestHttp::ERROR) : JsonResponse
        {
            return new JsonResponse([
                "status" => $error["status"] ?? 0,
                "message" => $error["message"] ?? "error",
                "data" => $data,
            ], $error["code"] ?? 500);
        }

        public function jsondecode(){
            try {
                return file_get_contents("php://input")?
                       json_decode(file_get_contents("php://input"), false) : [];
            } catch (\Exception $e) {
                return [];
            }
        }

        // Récupérer un fichier depuis Firebase Storage
        public function getImageFirebaseStorage()
        {
            $bucket = $this->storage->getBucket();
            $fileContents = $bucket->object('images/file.jpg')->downloadAsString();
        }

        // Récupérer un fichier depuis Firebase Storage
        public function setImageFirebaseStorage()
        {
            $bucket = $this->storage->getBucket();
            $bucket->upload(fopen('../templates/assets/images/email_logo.png', 'r'), [
                'name' => 'images/email_logo.png',
            ]);
        }


        public function em() : ObjectManager
        {
            return $this->managerRegistry->getManager();
        }


        public function hasher() : UserPasswordHasherInterface
        {
            return $this->encoder;
        }

        function generateUUID() {
            $uuid = Uuid::uuid4()->toString();
            return $uuid;
        }
        
    }


?>