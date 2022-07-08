Feature: basics
  In order to ensure that classes get marked as final
  As a library developer
  I need to have static analysis tools that have errors when classes are not final or marked for inheritance

  Background:
    Given I have the following config
      """
      <?xml version="1.0"?>
      <psalm errorLevel="8">
        <projectFiles>
          <directory name="."/>
        </projectFiles>
        <plugins>
          <pluginClass class="Cspray\Phinal\Plugin" />
        </plugins>
      </psalm>
      """
  Scenario: concrete class not marked final
    Given I have the following code
      """
      <?php

      class NotFinal {}
      """
    When I run Psalm
    Then I see these errors
      | Type          | Message |
      | ClassNotFinal | /NotFinal has not been marked as final nor is marked for inheritance/ |

  Scenario: abstract class
    Given I have the following code
      """
      <?php

      abstract class AbstractClass {}
      """
    When I run Psalm
    Then I see no errors

  Scenario: interface
    Given I have the following code
      """
      <?php

      interface SomeInterface {}
      """
    When I run Psalm
    Then I see no errors

  Scenario: concrete class marked final
    Given I have the following code
      """
      <?php

      final class SomeFinalClass {}
      """
    When I run Psalm
    Then I see no errors

  Scenario: anonymous class
    Given I have the following code
      """
      <?php

      interface SomeInterface {}

      $class = new class implements SomeInterface {};
      """
    When I run Psalm
    Then I see no errors

  Scenario: has attribute allowing inheritance
    Given I have the following code
      """
      <?php

      use Cspray\Phinal\AllowInheritance;

      #[AllowInheritance('We are allowing inheritance because we could not figure out how to use composition')]
      class SomeClassThatGetsInherited {}
      """
    When I run psalm
    Then I see no errors