<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImageUploaderHelper {

    private $slugger;
    private $translator;
    private $params;
    private $flash;
    private $imageDirectory;

    public function __construct(string $imageDirectory, SluggerInterface $slugger, TranslatorInterface $translator, ParameterBagInterface $params) {
        $this->slugger = $slugger;
        $this->translator = $translator;
        $this->params = $params;
        $this->imageDirectory = $imageDirectory;
        }

    public function uploadImage($form, $toto): String {
        $errorMessage = "";
        $imageFile = $form->get('image')->getData();

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
           
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
        
            try {
                $imageFile->move(
                    $this->params->get('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
               $errorMessage = $e->getMessage();
            }
            $toto->setImage($newFilename);
        }
        return $errorMessage;
    }
}