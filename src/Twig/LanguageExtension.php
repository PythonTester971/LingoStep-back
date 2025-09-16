<?php

namespace App\Twig;

use App\Repository\LanguageRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LanguageExtension extends AbstractExtension
{
  private LanguageRepository $languageRepository;

  public function __construct(LanguageRepository $languageRepository)
  {
    $this->languageRepository = $languageRepository;
  }

  public function getFunctions(): array
  {
    return [
      new TwigFunction('get_languages', [$this, 'getLanguages']),
    ];
  }

  public function getLanguages(): array
  {
    return $this->languageRepository->findBy([], ['label' => 'ASC'], 4);
  }
}
