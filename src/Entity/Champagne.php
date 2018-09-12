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
    private $name;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="float", length=255)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlLink;


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
    private $photo_1;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_1")
     * @var File
     */
    private $photoFile_1;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $photo_2;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_2")
     * @var File
     */
    private $photoFile_2;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $photo_3;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_3")
     * @var File
     */
    private $photoFile_3;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $photo_paragraph;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_paragraph")
     * @var File
     */
    private $photoFile_paragraph;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $photo_bouteille;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_bouteille")
     * @var File
     */
    private $photoFile_bouteille;


    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
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

    public function setPhotoFile1(File $image = null)
    {
        $this->photoFile_1 = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPhotoFile1()
    {
        return $this->photoFile_1;
    }

    public function setPhoto1($image)
    {
        $this->photo_1 = $image;
    }

    public function getphoto1()
    {
        return $this->photo_1;
    }

    public function setPhotoFile2(File $image = null)
    {
        $this->photoFile_2 = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPhotoFile2()
    {
        return $this->photoFile_2;
    }

    public function setPhoto2($image)
    {
        $this->photo_2 = $image;
    }

    public function getphoto2()
    {
        return $this->photo_2;
    }

    public function setPhotoFile3(File $image = null)
    {
        $this->photoFile_3 = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPhotoFile3()
    {
        return $this->photoFile_3;
    }

    public function setPhoto3($image)
    {
        $this->photo_3 = $image;
    }

    public function getphoto3()
    {
        return $this->photo_3;
    }

    public function setPhotoFileParagraph(File $image = null)
    {
        $this->photoFile_paragraph = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPhotoFileParagraph()
    {
        return $this->photoFile_paragraph;
    }

    public function setPhotoParagraph($image)
    {
        $this->photo_paragraph= $image;
    }

    public function getphotoParagraph()
    {
        return $this->photo_paragraph;
    }

    public function setPhotoFileBouteille(File $image = null)
    {
        $this->photoFile_bouteille = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPhotoFileBouteille()
    {
        return $this->photoFile_bouteille;
    }

    public function setPhotoBouteille($image)
    {
        $this->photo_bouteille = $image;
    }

    public function getphotoBouteille()
    {
        return $this->photo_bouteille;
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

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUrlLink()
    {
        return $this->urlLink;
    }

    /**
     * @param mixed $urlLink
     */
    public function setUrlLink($urlLink): void
    {
        $this->urlLink = $urlLink;
    }
}
