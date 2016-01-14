<?php

namespace Drupal\Tests\eck\Unit;

use Drupal\Tests\UnitTestCase;

abstract class UnitTestBase extends UnitTestCase {

  protected function createLanguageManagerMock() {
    $current_language_mock = $this->getMockForAbstractClass('\Drupal\Core\Language\LanguageInterface');
    $current_language_mock->method('id')->willReturn('en');

    $mock = $this->getMockForAbstractClass('\Drupal\Core\Language\LanguageManagerInterface');
    $mock->method('getCurrentLanguage')->willReturn($current_language_mock);

    return $mock;
  }

}
