<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChampagneRepository")
 * @Vich\Uploadable
 */
class Champagne
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title_1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subtitle_1;

    /**
     * @ORM\Column(type="text")
     */
    private $presentation_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subtitle_2_1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation_2_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subtitle_2_2;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation_2_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subtitle_2_3;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation_2_3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title_3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subtitle_4;

    /**
     * @ORM\Column(type="text")
     */
    private $presentation_4;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle1(): ?string
    {
        return $this->title_1;
    }

    public function setTitle1(string $title_1): self
    {
        $this->title_1 = $title_1;

        return $this;
    }

    public function getSubtitle1(): ?string
    {
        return $this->subtitle_1;
    }

    public function setSubtitle1(string $subtitle_1): self
    {
        $this->subtitle_1 = $subtitle_1;

        return $this;
    }

    public function getPresentation1(): ?string
    {
        return $this->presentation_1;
    }

    public function setPresentation1(string $presentation_1): self
    {
        $this->presentation_1 = $presentation_1;

        return $this;
    }

    public function getSubtitle21(): ?string
    {
        return $this->subtitle_2_1;
    }

    public function setSubtitle21(?string $subtitle_2_1): self
    {
        $this->subtitle_2_1 = $subtitle_2_1;

        return $this;
    }

    public function getPresentation21(): ?string
    {
        return $this->presentation_2_1;
    }

    public function setPresentation21(?string $presentation_2_1): self
    {
        $this->presentation_2_1 = $presentation_2_1;

        return $this;
    }

    public function getSubtitle22(): ?string
    {
        return $this->subtitle_2_2;
    }

    public function setSubtitle22(?string $subtitle_2_2): self
    {
        $this->subtitle_2_2 = $subtitle_2_2;

        return $this;
    }

    public function getPresentation22(): ?string
    {
        return $this->presentation_2_2;
    }

    public function setPresentation22(?string $presentation_2_2): self
    {
        $this->presentation_2_2 = $presentation_2_2;

        return $this;
    }

    public function getSubtitle23(): ?string
    {
        return $this->subtitle_2_3;
    }

    public function setSubtitle23(?string $subtitle_2_3): self
    {
        $this->subtitle_2_3 = $subtitle_2_3;

        return $this;
    }

    public function getPresentation23(): ?string
    {
        return $this->presentation_2_3;
    }

    public function setPresentation23(?string $presentation_2_3): self
    {
        $this->presentation_2_3 = $presentation_2_3;

        return $this;
    }

    public function getTitle3(): ?string
    {
        return $this->title_3;
    }

    public function setTitle3(?string $title_3): self
    {
        $this->title_3 = $title_3;

        return $this;
    }

    public function getSubtitle4(): ?string
    {
        return $this->subtitle_4;
    }

    public function setSubtitle4(string $subtitle_4): self
    {
        $this->subtitle_4 = $subtitle_4;

        return $this;
    }

    public function getPresentation4(): ?string
    {
        return $this->presentation_4;
    }

    public function setPresentation4(string $presentation_4): self
    {
        $this->presentation_4 = $presentation_4;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
