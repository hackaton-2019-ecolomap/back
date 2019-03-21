<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "swagger_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "region",
 *                          "in" = "query",
 *                          "description" = "Region",
 *                          "required" = "true",
 *                          "type" : "string"
 *                      }
 *                  }
 *               }
 *          }
 *     },
 *     itemOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
    private $parent;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $children = [];




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(string $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getChildren(): ?array
    {
        return $this->children;
    }

    public function setChildren(?array $children): self
    {
        $this->children = $children;

        return $this;
    }
}
