<?php

    namespace App\Service;

    use App\Entity\UserSertified;
    use App\Entity\UserValidation;
    use App\Repository\RolesRepository;
    use App\Repository\UserRepository;
    use App\Repository\UserSertifiedRepository;
    use App\Repository\UserValidationRepository;
    use Mpdf\Mpdf;
    use App\Utils\Globals;
    use App\Utils\SECURITY_UTILS;
    use App\Utils\UTILS;
    use DateTime;
    use Endroid\QrCode\QrCode;
    use Endroid\QrCode\Writer\PngWriter;
    use Ramsey\Uuid\Uuid;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use Symfony\Component\HttpFoundation\Response;
    use Twig\Environment;
    use Endroid\QrCode\Color\Color;
    use Endroid\QrCode\Encoding\Encoding;
    use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;

    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;

    class EmailService 
    {
        private Globals $globals;

        /**
         * @var MailerInterface
         */
        private $mailer;

        /**
         * @var Environment
         */
        private $tweg;

        /**
         * @var UserValidationRepository
         */
        private $userValidationRepository;

        /**
         * @var UserRepository
         */
        private $userRepository;

        /**
         * @var RolesRepository
         */
        private $rolesRepository;

        /**
         * @var UserSertifiedRepository
         */
        private $userSertifiedRepository;

        /**
         * @param MailerInterface $mailer
         * @param Environment $tweg
         * @param UserValidationRepository $userValidationRepository
         * @param UserRepository $userRepository
         * @param RolesRepository $rolesRepository
         * @param UserSertifiedRepository $userSertifiedRepository
        */
        public function __construct(
            Globals $globals, 
            MailerInterface $mailer, 
            Environment $tweg,
            UserValidationRepository $userValidationRepository,
            UserRepository $userRepository,
            RolesRepository $rolesRepository,
            UserSertifiedRepository $userSertifiedRepository,
        )
        {
            $this->globals = $globals;
            $this->mailer = $mailer;
            $this->tweg = $tweg;
            $this->userRepository = $userRepository;
            $this->userValidationRepository = $userValidationRepository;
            $this->rolesRepository = $rolesRepository;
            $this->userSertifiedRepository = $userSertifiedRepository;
        }

        /**
         * @param Object $data
         * @throws |Symfony|Componenet|Mailer|Exception|TransportExceptionInterface 
         * @throws |tweg|Error|LoadError
         * @throws |tweg|Error|RuntimeError
         * @throws |tweg|Error|SyntaxError
         * 
         */
        public function sendEmailValidationRegister(Object $data) 
        {
            $subject = 'Hello from AEESCOS Commission';
            $templates = 'emails/signup.html.twig';
            $code = $this->CodeValidation();
            
            $dTS = $this->GetDateTime_Send_Or_Sertified();
            $dTE = $this->GetTimeExpValidation();

            $codeEmail = SECURITY_UTILS::CODE_FOR_USER($code);
            $codeIndata = SECURITY_UTILS::CODE_IN_DATA($code);
            $parametres = [
                "name" => $data->nom,
                "mailer" => "Valide E-mail",
                "type_mailer" => "Validation",
                "message" => "salut",
                "email" => $data->email,
                'timeSend' => $dTS,
                'timeExp' => $dTE,
                'codeValidation' => $codeEmail,
            ];
            $email = (new Email())
            ->from($this->globals->EmailAdmin())
            ->to($data->email)
            ->subject($subject)
            ->embed(fopen('../templates/assets/images/email_logo.png', 'r'), 'logo')
            ->html(
                $this->tweg->render($templates, $parametres), "text/html"
            );
            $isAddValid = $this->userValidationRepository->registerUser($dTE, $data->email, $codeIndata);
            if (strcmp($isAddValid, UTILS::ADD) == 0) {
                $this->globals->TRANSPORT()->send($email);
                return UTILS::ADD;
            } else {
                return UTILS::ERROR;
            }
        }


                /**
         * @param Object $data
         * @throws |Symfony|Componenet|Mailer|Exception|TransportExceptionInterface 
         * @throws |tweg|Error|LoadError
         * @throws |tweg|Error|RuntimeError
         * @throws |tweg|Error|SyntaxError
         * 
         */
        public function resendCode(Object $data) 
        {
            $subject = 'Hello from AEESCOS Commission';
            $templates = 'emails/signup.html.twig';
            $code = $this->CodeValidation();
            
            $dTS = $this->GetDateTime_Send_Or_Sertified();
            $dTE = $this->GetTimeExpValidation();

            $codeEmail = SECURITY_UTILS::CODE_FOR_USER($code);
            $codeIndata = SECURITY_UTILS::CODE_IN_DATA($code);
            $user = $this->userRepository->findOneBy(['email' => $data->email]);
            $userValide = $this->userValidationRepository->findOneBy(['email' => $data->email]);
            $parametres = [
                "name" => $user->getNom(),
                "mailer" => "Valide E-mail",
                "type_mailer" => "Validation",
                "message" => "salut",
                "email" => $user->getEmail(),
                'timeSend' => $dTS,
                'timeExp' => $dTE,
                'codeValidation' => $codeEmail,
            ];
            $email = (new Email())
                ->from($this->globals->EmailAdmin())
                ->to($userValide->getEmail())
                ->subject($subject)
                ->embed(fopen('../templates/assets/images/email_logo.png', 'r'), 'logo')
                ->html(
                    $this->tweg->render($templates, $parametres), "text/html"
                );
            $isAddValid = $this->userValidationRepository->update($dTE, $userValide->getId(), $userValide->getEmail(), $codeIndata);
            if (strcmp($isAddValid, UTILS::ADD) == 0) {
                $this->globals->TRANSPORT()->send($email);
                return UTILS::ADD;
            } else {
                return UTILS::ERROR;
            }
        }


        private function CodeValidation(): string
        {
            $uuid = Uuid::uuid4();
            return substr($uuid->toString(), 0, strpos($uuid->toString(), '-'));
        }
    
        private function GetTimeExpValidation(): string
        {
            $myFormatObj = 'Y-m-d H:i:s';
            $expirationDateTime = new DateTime();
            $expirationDateTime->modify(UTILS::VALIDATION_DURATION_MINUTES);
            return $expirationDateTime->format($myFormatObj);
        }

        private function GetDateTime_Send_Or_Sertified(): string
        {
            $myFormatObj = 'Y-m-d H:i:s';
            $expirationDateTime = new DateTime();
            return $expirationDateTime->format($myFormatObj);
        }



        public function buildExcelDocument(Response $response, array $model): string
        {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $list = $model['usersList'];

            $sheet->setCellValue('A1', 'NOM');
            $sheet->setCellValue('B1', 'PRENOM');
            $sheet->setCellValue('C1', 'EMAIL');

            $rowNumber = 2;
            foreach ($list as $user) {
                $sheet->setCellValue('A' . $rowNumber, $user->getNnom());
                $sheet->setCellValue('B' . $rowNumber, $user->getPrenom());
                $sheet->setCellValue('C' . $rowNumber, $user->getEmail());
                $rowNumber++;
            }

            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'excel');
            $writer->save($tempFile);

            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="liste_des_users.xlsx"');
            $response->setContent(file_get_contents($tempFile));

            return $response;
        }

        public function writePdf(Response $response, array $model, Environment $twig): Response
        {
            $list = $model['usersList'];

            // Récupérer les données de l'image
            $imagePath = '/chemin/vers/votre/image.png';
            $imageData = base64_encode(file_get_contents($imagePath));
        
            // Passer les données de l'image à la vue Twig
            $html = $twig->render('pdf_generator/index.html.twig', [
                'list' => $list,
                'imageData' => $imageData,
            ]);


            $pdf = new Mpdf();
            $pdf->SetHeader('Liste des Utilisateurs.');
            $pdf->SetTitle('Liste des Utilisateurs.');
            $pdf->WriteHTML($html);
            $pdf->Output('liste_des_utilisateurs.pdf', 'D');

            return $response;
        }

        public function generatePdf(Response $response, array $model, Environment $twig): Response
        {
            $list = $model['usersList'];
            
            // Récupérer les données de l'image
            $imagePath = '/chemin/vers/votre/image.png';
            $imageData = base64_encode(file_get_contents($imagePath));

            // Passer les données de l'image à la vue Twig
            $html = $twig->render('pdf_generator/index.html.twig', [
                'list' => $list,
                'imageData' => $imageData,
            ]);

            $pdf = new Mpdf();
            $pdf->SetHeader('Liste des Utilisateurs.');
            $pdf->SetTitle('Liste des Utilisateurs.');
            $pdf->WriteHTML($html);
            $pdfContent = $pdf->Output('', 'S');

            $response = new Response($pdfContent);
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment; filename="liste_des_utilisateurs.pdf"');

            return $response;
        }


        public function generateQRCode(string $data): Response
        {

            $qrCode = QrCode::create($data)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

            // Create writer
            $writer = new PngWriter();

            // Generate QR code image
            $qrCodeData = $writer->write($qrCode)->getString();

            // Create response with image data
            $response = new Response($qrCodeData);
            $response->headers->set('Content-Type', 'image/png');
        
            return $response;
        }
        

        public function valideCompte(Object $dataUserV): string
        {
             $userV = (new UserValidation())
                ->setEmail($dataUserV->email)
                ->setCodeValidation($dataUserV->codeValidation);
            if ($this->userValidationRepository->findOneBy(['email' => $userV->getEmail()]) !== null && $this->userRepository->findOneBy(['email' => $userV->getEmail()]) !== null) {
                $userValide = $this->userValidationRepository->findOneBy(['email' => $userV->getEmail()]);
                if (strcasecmp($this->VerifTimeSend($userValide->getTExpire(), $userValide->getDExpire()), UTILS::VALIDE_CODE) === 0) {
                    if (strcasecmp(trim(str_replace(UTILS::SPECIAL_CHARACTERS_O, UTILS::SPECIAL_CHARACTERS_I, $userV->getCodeValidation())), trim($userValide->getCodeValidation())) === 0) {
                        $getuser = $this->userRepository->findOneBy(['email' => $userV->getEmail()]);
                        $getuser->setSertified(true);
                        
                        
                        $uss = (new UserSertified())
                        ->setDSertified($this->GetDateTime_Send_Or_Sertified())
                        ->setEmail($getuser->getEmail())
                        ->setName($getuser->getNom());
                        return  $this->userSertifiedRepository->registerUser($uss);
                    } 
                    else return UTILS::CODE_INVALIDE;
                } 
                else return UTILS::EXPIRED_CODE;
            } 
            else return UTILS::EMAIL_NOT_FOUND;
        }


        private function VerifTimeSend($TimeExp, $dateExp) {
            $myFormatObj = UTILS::FORMAT_TIME;
            $myObj = new DateTime();
            $TimeSend = $myObj->format($myFormatObj);
        
            $DateObj = UTILS::FORMAT_DATE;
            $dateObjet = new DateTime();
            $dateSend = $dateObjet->format($DateObj);
        
            return $this->CheckTime($TimeExp, $dateExp, $TimeSend, $dateSend);
        }
        
        private function CheckTime($TimeExp, $dateExp, $TimeSend, $dateSend) {
            $expirationDateTime = DateTime::createFromFormat(UTILS::FORMAT_DATE_TIME, $dateExp . ' ' . $TimeExp);
            $sendDateTime = DateTime::createFromFormat(UTILS::FORMAT_DATE_TIME, $dateSend . ' ' . $TimeSend);
        

            $expirationPlus10Min = $expirationDateTime->modify(UTILS::VALIDATION_DURATION_MINUTES);
            //$expirationPlus10Min = $expirationDateTime->modify('+10 minutes');

        
            if ($sendDateTime > $expirationPlus10Min) {
                return UTILS::EXPIRED_CODE;
            } else {
                return UTILS::VALIDE_CODE;
            }
        }
        



    }


?>