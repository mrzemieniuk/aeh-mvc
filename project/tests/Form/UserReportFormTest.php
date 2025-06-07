<?php

namespace App\Tests\Form;

use App\Entity\UserReport;
use App\Enum\ThreatTypeEnum;
use App\Enum\UserReportStatusEnum;
use App\Form\UserReportForm;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class UserReportFormTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidator();
        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'location' => 'Test Location',
            'type' => ThreatTypeEnum::OTHER->name,
            'description' => 'This is a test description for the report',
            'status' => UserReportStatusEnum::PENDING->name,
        ];

        $userReport = new UserReport();
        $form = $this->factory->create(UserReportForm::class, $userReport);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        $this->assertEquals('Test Location', $userReport->getLocation());
        $this->assertEquals(ThreatTypeEnum::OTHER->name, $userReport->getType());
        $this->assertEquals('This is a test description for the report', $userReport->getDescription());
        $this->assertEquals(UserReportStatusEnum::PENDING->name, $userReport->getStatus());
    }

    public function testInvalidData(): void
    {
        $formData = [
            'location' => 'Te', // Too short
            'type' => ThreatTypeEnum::OTHER->name,
            'description' => 'Short', // Too short
            'status' => UserReportStatusEnum::PENDING->name,
        ];

        $userReport = new UserReport();
        $form = $this->factory->create(UserReportForm::class, $userReport);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());

        $this->assertTrue($form->get('location')->getErrors()->count() > 0);
        $this->assertTrue($form->get('description')->getErrors()->count() > 0);
    }

    public function testEmptyData(): void
    {
        $formData = [
            'location' => '',
            'type' => '',
            'description' => '',
            'status' => '',
        ];

        $userReport = new UserReport();
        $form = $this->factory->create(UserReportForm::class, $userReport);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());

        $this->assertTrue($form->get('location')->getErrors()->count() > 0);
        $this->assertTrue($form->get('type')->getErrors()->count() > 0);
        $this->assertTrue($form->get('description')->getErrors()->count() > 0);
        $this->assertTrue($form->get('status')->getErrors()->count() > 0);
    }
}
