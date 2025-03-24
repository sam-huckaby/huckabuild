<?php

namespace Foundry\Controllers;

use Foundry\Models\Media;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\UploadedFile;

class MediaAdminController
{
    private $uploadDirectory = __DIR__ . '/../../storage/media';

    public function index(Request $request, Response $response)
    {
        $media = Media::orderBy('created_at', 'desc')->get();
        return $this->container->get('view')->render($response, 'admin/media/index.twig', [
            'media' => $media
        ]);
    }

    public function create(Request $request, Response $response)
    {
        return $this->container->get('view')->render($response, 'admin/media/create.twig');
    }

    public function store(Request $request, Response $response)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $data = $request->getParsedBody();

        if (empty($uploadedFiles['file'])) {
            throw new \Exception('No file was uploaded.');
        }

        $file = $uploadedFiles['file'];
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new \Exception('Upload failed with error code ' . $file->getError());
        }

        $filename = $this->moveUploadedFile($file);

        $media = new Media();
        $media->filename = $filename;
        $media->original_filename = $file->getClientFilename();
        $media->file_path = '/storage/media/' . $filename;
        $media->file_size = $file->getSize();
        $media->mime_type = $file->getClientMediaType();
        $media->description = $data['description'] ?? null;
        $media->save();

        return $response->withHeader('Location', '/admin/media')->withStatus(302);
    }

    public function edit(Request $request, Response $response, $args)
    {
        $media = Media::findOrFail($args['id']);
        return $this->container->get('view')->render($response, 'admin/media/edit.twig', [
            'media' => $media
        ]);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $media = Media::findOrFail($args['id']);
        
        $media->description = $data['description'] ?? null;
        $media->save();

        return $response->withHeader('Location', '/admin/media')->withStatus(302);
    }

    public function delete(Request $request, Response $response, $args)
    {
        $media = Media::findOrFail($args['id']);
        
        // Delete the physical file
        $filePath = __DIR__ . '/../../public' . $media->file_path;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $media->delete();

        return $response->withHeader('Location', '/admin/media')->withStatus(302);
    }

    private function moveUploadedFile(UploadedFile $file)
    {
        if (!is_dir($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0755, true);
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $file->moveTo($this->uploadDirectory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
} 