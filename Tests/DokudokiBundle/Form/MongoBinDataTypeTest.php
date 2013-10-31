<?php

/*
 * DokudokiBundle
 */

namespace tests\DokudokiBundle\Form;

use tests\DokudokiBundle\Form\FunctionalTestForm;
use Trismegiste\DokudokiBundle\Form\MongoBinDataType;

/**
 * MongoBinDataTypeTest is a test for the MongoBinDataType
 */
class MongoBinDataTypeTest extends FunctionalTestForm
{

    private function createUploadedFileMock($name, $originalName, $valid)
    {

        $file
                ->expects($this->any())
                ->method('getBasename')
                ->will($this->returnValue($name))
        ;
        $file
                ->expects($this->any())
                ->method('getClientOriginalName')
                ->will($this->returnValue($originalName))
        ;
        $file
                ->expects($this->any())
                ->method('isValid')
                ->will($this->returnValue($valid))
        ;

        return $file;
    }

    public function testBindingFile()
    {
        $file = $this
                ->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
                ->disableOriginalConstructor()
                ->getMock();
        $file
                ->expects($this->any())
                ->method('getRealPath')
                ->will($this->returnValue(__DIR__ . '/../../doc/img/atomicity.jpg'));

        $form = $this->formFactory->create(new MongoBinDataType());
        $form->bind($file);

        $this->assertInstanceOf('MongoBinData', $form->getData());
    }

}