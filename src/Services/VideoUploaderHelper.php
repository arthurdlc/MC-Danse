<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class VideoUploaderHelper {

    private $slugger;
    private $translator;
    private $params;
    private $flash;
    private $videoDirectory;

    public function __construct(string $videoDirectory, SluggerInterface $slugger, TranslatorInterface $translator, ParameterBagInterface $params) {
        $this->slugger = $slugger;
        $this->translator = $translator;
        $this->params = $params;
        $this->videoDirectory = $videoDirectory;
    }

    public function uploadVideo($form, $entity): string {
        $errorMessage = "";

        $videoFile = $form->get('videoPath')->getData();

        if ($videoFile) {
            $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$videoFile->guessExtension();

            try {
                $videoFile->move(
                    $this->params->get('video_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
               $errorMessage = $e->getMessage();
            }

            $entity->setVideoPath($newFilename);
        }

        return $errorMessage;
    }
}
