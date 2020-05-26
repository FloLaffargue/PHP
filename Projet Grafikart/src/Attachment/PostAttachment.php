<?php

namespace App\Attachment;

use App\Model\Post;
use Intervention\Image\ImageManager;

class PostAttachment {

    const DIRECTORY = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'post';
    public static function upload(Post $post) 
    {
        // On récupére le chemin temporaire
        if(empty($post->getImage()) || !$post->shouldUpload()) {
            return;
        }

        $directory = self::DIRECTORY;

        if(!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        if(!empty($post->getOldImage())) {

            // Permet de supprimer une image
            $formats = ['small', 'large'];

            foreach($formats as $format) {
                $oldFile = $directory . DIRECTORY_SEPARATOR . $post->getOldImage() . '_' . $format . '.jpg';
                if(\file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
        }

        $filename = uniqid("", true);

        // Permet de redimensionner les images
        $manager = new ImageManager(['driver' => 'gd']);
        $manager
            ->make($post->getImage())
            ->fit(350,200)
            ->save($directory . DIRECTORY_SEPARATOR . $filename . '_small.jpg');
        $manager
            ->make($post->getImage())
            ->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($directory . DIRECTORY_SEPARATOR . $filename . '_large.jpg');

        $post->setImage($filename);
    }

    public static function detache(Post $post)
    {
        if(!empty($post->getImage())) {

            // Permet de supprimer une image
            $formats = ['small', 'large'];

            foreach($formats as $format) {
                $file = self::DIRECTORY . DIRECTORY_SEPARATOR . $post->getImage() . '_' . $format . '.jpg';
                if(\file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }
}