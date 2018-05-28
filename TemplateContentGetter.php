<?php

namespace InterfaceGenerator;


trait TemplateContentGetter
{
    /**
     * @return string шаблон интерфейса
     */
    protected function getTemplateInterface()
    {
        return $this->getTemplateContent(
            'interface.txt'
        );
    }

    /**
     * @return string шаблон метода интерфейса
     */
    protected function getTemplateInterfaceMethod()
    {
        return $this->getTemplateContent(
            'interfaceMethod.txt'
        );
    }

    /**
     * @return string шаблон комментария
     */
    protected function getTemplateMethodComment()
    {
        return $this->getTemplateContent(
            'methodComment.txt'
        );
    }

    /**
     * @return string шаблон коментария-описания метода
     */
    protected function getTemplateDescriptionComment()
    {
        return $this->getTemplateContent(
            'methodDescriptionComment.txt'
        );
    }

    /**
     * @return string шаблон коментария к параметру метода
     */
    protected function getTemplateParameterComment()
    {
        return $this->getTemplateContent(
            'parameterComment.txt'
        );
    }

    /**
     * @return string шаблон коментария возвращаемого значения метода
     */
    protected function getTemplateReturnComment()
    {
        return $this->getTemplateContent(
            'returnComment.txt'
        );
    }

    /**
     * @return string шаблон параметра метода
     */
    protected function getTemplateMethodParameter()
    {
        return $this->getTemplateContent(
            'methodParameter.txt'
        );
    }

    /**
     * @param string $templateFileName имя файла шаблона
     * @return string содержимое шаблона
     */
    protected function getTemplateContent($templateFileName)
    {
        return file_get_contents(
            __DIR__ .
            DIRECTORY_SEPARATOR.
            'Templates'.
            DIRECTORY_SEPARATOR.
            $templateFileName
        );
    }
}