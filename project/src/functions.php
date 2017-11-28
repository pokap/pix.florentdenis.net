<?php

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type as Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Save a file with a thumbnail.
 *
 * @param UploadedFile $file
 * @param int          $width
 *
 * @return string Filename
 */
function saveFile(UploadedFile $file, $width)
{
    $filename = md5(mt_rand().$file->getBasename()).'.'.$file->guessClientExtension();

    $file->move(__DIR__.'/../../img', $filename);

    $imagine = new Imagine\Gd\Imagine();
    $image = $imagine->open(__DIR__.'/../../img/'.$filename);

    $box = $image->getSize();
    $box = $box->widen((int) $width);

    $image = $image->thumbnail($box, Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
    $image->save(__DIR__.'/../../img/mini_'.$filename);

    return $filename;
}

/**
 * Create a form of upload.
 *
 * @param FormFactoryInterface $factory
 *
 * @return \Symfony\Component\Form\FormInterface
 */
function createUploadForm(FormFactoryInterface $factory)
{
    $formBuilder = $factory->createBuilder();
    $formBuilder->add('largeur', Form\IntegerType::class, [
        'data' => 500,
        'constraints' => [
            new Assert\NotBlank(),
        ],
    ]);
    $formBuilder->add('image', Form\FileType::class, [
        'constraints' => [
            new Assert\NotBlank(),
            new Assert\File(['maxSize' => '1M', 'mimeTypes' => ['image/png', 'image/jpg', 'image/jpeg']]),
        ],
    ]);

    return $formBuilder->getForm();
}
