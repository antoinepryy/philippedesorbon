<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=255)
     */
    private $subtitle_2_1;

    /**
     * @ORM\Column(type="text")
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
    private $subtitle_3;
    

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subtitle_4;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation_4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subtitle_5;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation_5;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $photo_1;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_1")
     * @var File
     */
    private $photoFile_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $photo_2;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_2")
     * @var File
     */
    private $photoFile_2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $photo_3;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_3")
     * @var File
     */
    private $photoFile_3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $photo_4;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_4")
     * @var File
     */
    private $photoFile_4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $photo_paragraph_1;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_paragraph_1")
     * @var File
     */
    private $photoFile_paragraph_1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $photo_paragraph_2;

    /**
     * @Vich\UploadableField(mapping="champagne_images", fileNameProperty="photo_paragraph_2")
     * @var File
     */
    private $photoFile_paragraph_2;

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Option", mappedBy="champagne", orphanRemoval=true)
     */
    private $options;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }


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

    public function getSubtitle1()
    {
        return $this->subtitle_1;
    }

    public function setSubtitle1($subtitle_1)
    {
        $this->subtitle_1 = $subtitle_1;

        return $this;
    }

    public function getPresentation1()
    {
        return $this->presentation_1;
    }

    public function setPresentation1($presentation_1)
    {
        $this->presentation_1 = $presentation_1;

        return $this;
    }

    public function getSubtitle21()
    {
        return $this->subtitle_2_1;
    }

    public function setSubtitle21($subtitle_2_1)
    {
        $this->subtitle_2_1 = $subtitle_2_1;

        return $this;
    }

    public function getPresentation21()
    {
        return $this->presentation_2_1;
    }

    public function setPresentation21($presentation_2_1)
    {
        $this->presentation_2_1 = $presentation_2_1;

        return $this;
    }

    public function getSubtitle22()
    {
        return $this->subtitle_2_2;
    }

    public function setSubtitle22($subtitle_2_2)
    {
        $this->subtitle_2_2 = $subtitle_2_2;

        return $this;
    }

    public function getPresentation22()
    {
        return $this->presentation_2_2;
    }

    public function setPresentation22($presentation_2_2)
    {
        $this->presentation_2_2 = $presentation_2_2;

        return $this;
    }

    public function getSubtitle23()
    {
        return $this->subtitle_2_3;
    }

    public function setSubtitle23($subtitle_2_3)
    {
        $this->subtitle_2_3 = $subtitle_2_3;

        return $this;
    }

    public function getPresentation23()
    {
        return $this->presentation_2_3;
    }

    public function setPresentation23($presentation_2_3)
    {
        $this->presentation_2_3 = $presentation_2_3;

        return $this;
    }

    public function getSubTitle3()
    {
        return $this->subtitle_3;
    }

    public function setSubTitle3($subtitle_3)
    {
        $this->subtitle_3 = $subtitle_3;

        return $this;
    }
    
    public function getSubtitle4()
    {
        return $this->subtitle_4;
    }

    public function setSubtitle4($subtitle_4)
    {
        $this->subtitle_4 = $subtitle_4;

        return $this;
    }

    public function getPresentation4()
    {
        return $this->presentation_4;
    }

    public function setPresentation4($presentation_4)
    {
        $this->presentation_4 = $presentation_4;

        return $this;
    }


    public function getSubtitle5(): ?string
    {
        return $this->subtitle_5;
    }

    public function setSubtitle5($subtitle_5)
    {
        $this->subtitle_5 = $subtitle_5;

        return $this;
    }

    public function getPresentation5()
    {
        return $this->presentation_5;
    }

    public function setPresentation5($presentation_5)
    {
        $this->presentation_5 = $presentation_5;

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

    public function setPhotoFile4(File $image = null)
    {
        $this->photoFile_4 = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPhotoFile4()
    {
        return $this->photoFile_4;
    }

    public function setPhoto4($image)
    {
        $this->photo_4 = $image;
    }

    public function getphoto4()
    {
        return $this->photo_4;
    }


    public function setPhotoFileParagraph1(File $image = null)
    {
        $this->photoFile_paragraph_1 = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPhotoFileParagraph1()
    {
        return $this->photoFile_paragraph_1;
    }

    public function setPhotoParagraph1($image)
    {
        $this->photo_paragraph_1= $image;
    }

    public function getPhotoParagraph1()
    {
        return $this->photo_paragraph_1;
    }

    public function setPhotoFileParagraph2(File $image = null)
    {
        $this->photoFile_paragraph_2 = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getPhotoFileParagraph2()
    {
        return $this->photoFile_paragraph_2;
    }

    public function setPhotoParagraph2($image)
    {
        $this->photo_paragraph_2= $image;
    }

    public function getphotoParagraph2()
    {
        return $this->photo_paragraph_2;
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

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->setChampagne($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            // set the owning side to null (unless already changed)
            if ($option->getChampagne() === $this) {
                $option->setChampagne(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }

    public function hasOptions(){
        if ($this->getOptions()[0] === null){
            return false;
        }
        else{
            return true;
        }
    }
}
