<?php

/*
 * DokudokiBundle
 */

namespace Trismegiste\DokudokiBundle\Tests\Form;

use Trismegiste\DokudokiBundle\Tests\Form\FunctionalTestForm;
use Trismegiste\DokudokiBundle\Form\MongoBinDataType;

/**
 * MongoFileTypeTest is a test for the MongoBinDataType
 * @author florent
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
                ->will($this->returnValue('./Resources/doc/img/atomicity.jpg'));

        $form = $this->formFactory->create(new MongoBinDataType());
        $form->bind($file);

        $this->assertInstanceOf('MongoBinData', $form->getData());
    }

}