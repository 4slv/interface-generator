<?php

namespace InterfaceGenerator;
use Slov\Helper\FileHelper;
use Slov\Helper\StringHelper;

/** Генератор интерфейса */
class InterfaceGenerator
{
    use TemplateContentGetter;

    /** @var string путь к папке проекта */
    protected $projectPath;

    /** @var string относительный путь к папке в которой будет сгенерирован код интерфейса */
    protected $interfaceCodeRelativePath;

    /** @var string пространство имён */
    protected $namespace;

    /** @var string комментарий к интерфейсу */
    protected $interfaceComment;

    /** @var string название интерфейса */
    protected $interfaceName;

    /** @var InterfaceMethod[] список методов интерфейса */
    protected $interfaceMethodList = [];

    /** @var string[] список подключаемых классов */
    protected $useClassList = [];

    /**
     * @return string путь к папке проекта
     */
    public function getProjectPath(): string
    {
        return $this->projectPath;
    }

    /**
     * @param string $projectPath путь к папке проекта
     * @return $this
     */
    public function setProjectPath(string $projectPath)
    {
        $this->projectPath = $projectPath;
        return $this;
    }

    /**
     * @return string относительный путь к папке в которой будет сгенерирован код интерфейса
     */
    public function getInterfaceCodeRelativePath(): string
    {
        return $this->interfaceCodeRelativePath;
    }

    /**
     * @param string $interfaceCodeRelativePath относительный путь к папке в которой будет сгенерирован код интерфейса
     * @return $this
     */
    public function setInterfaceCodeRelativePath(string $interfaceCodeRelativePath)
    {
        $this->interfaceCodeRelativePath = $interfaceCodeRelativePath;
        return $this;
    }

    /**
     * @return string пространство имён
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace пространство имён
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string комментарий к интерфейсу
     */
    public function getInterfaceComment()
    {
        return $this->interfaceComment;
    }

    /**
     * @param string $interfaceComment комментарий к интерфейсу
     * @return $this
     */
    public function setInterfaceComment(string $interfaceComment)
    {
        $this->interfaceComment = $interfaceComment;
        return $this;
    }

    /**
     * @return string название интерфейса
     */
    public function getInterfaceName(): string
    {
        return $this->interfaceName;
    }

    /**
     * @param string $interfaceName название интерфейса
     * @return $this
     */
    public function setInterfaceName(string $interfaceName)
    {
        $this->interfaceName = $interfaceName;
        return $this;
    }

    /**
     * @return InterfaceMethod[] список методов интерфейса
     */
    public function getInterfaceMethodList(): array
    {
        return $this->interfaceMethodList;
    }

    /**
     * @param InterfaceMethod[] $interfaceMethodList список методов интерфейса
     * @return $this
     */
    public function setInterfaceMethodList(array $interfaceMethodList)
    {
        $this->interfaceMethodList = $interfaceMethodList;
        return $this;
    }

    /**
     * @return string[] список подключаемых классов
     */
    public function getUseClassList(): array
    {
        return $this->useClassList;
    }

    /**
     * @return string полный путь к папке с интерфейсом
     */
    protected function getInterfaceDirectoryPath()
    {
        return
            $this->getProjectPath().
            DIRECTORY_SEPARATOR.
            $this->getInterfaceCodeRelativePath();
    }

    /**
     * @return string полный путь к классу
     */
    protected function getInterfacePath()
    {
        return
            $this->getInterfaceDirectoryPath().
            DIRECTORY_SEPARATOR.
            $this->getInterfaceName(). '.php';
    }

    /**
     * @return string контент интерфейса
     */
    public function getInterfaceContent()
    {
        $this->initUseClassesList();
        return StringHelper::replacePatterns(
            $this->getTemplateInterface(),
            [
                '%namespace%' => $this->getNamespace(),
                '%useClasses%' => $this->getUseClassesContent(),
                '%interfaceComment%' => $this->formatComment($this->getInterfaceComment()),
                '%interfaceName%' => $this->getInterfaceName(),
                '%interfaceMethods%' => $this->getInterfaceMethodsContent()
            ]
        );
    }

    /** Инициализация списка подключаемых классов */
    protected function initUseClassesList()
    {
        $this->useClassList = [];
        foreach ($this->getInterfaceMethodList() as $interfaceMethod)
        {
            foreach ($interfaceMethod->getParameterList() as $parameter)
            {
                $this->tryAddClassToUseBlock($parameter->getFullType());
            }
            $this->tryAddClassToUseBlock($interfaceMethod->getReturnFullType());
        }
    }

    /**
     * Попытка добавить класс в подключаемые классы
     * @param string $classFullName полное имя класса с немспейсом
     */
    protected function tryAddClassToUseBlock($classFullName)
    {
        $classFullNameParts = explode('\\', $classFullName);
        $className = array_pop($classFullNameParts);
        $classNamespace = implode('\\', $classFullNameParts);

        if(
            # подключать нужно только классы
            $className === ucfirst($className)
            &&
            # подключать нужно только классы с неймспейсом отличным от текущего
            $classNamespace !== $this->getNamespace()
            &&
            # класс нужно подключать только если его название ещё не использовалось
            array_key_exists($className, $this->useClassList) === false
        )
        {
            $this->useClassList[$className] = $classFullName;
        }
    }

    /**
     * @return string контент подключаемых классов
     */
    protected function getUseClassesContent()
    {
        $useClasses = [];
        foreach ($this->getUseClassList() as $useClass)
        {
            $useClasses[] = "use $useClass;";
        }
        return implode("\n", $useClasses);
    }

    /**
     * @param string $commentText текст комментария
     * @return string комменарий к интерфейсу
     */
    protected function formatComment($commentText)
    {
        return strlen($commentText) > 0 ? '/** '. $commentText. ' */' : '';
    }

    /** @return string контент методов интерфейса */
    protected function getInterfaceMethodsContent()
    {
        $methodsContent = '';
        foreach ($this->getInterfaceMethodList() as $interfaceMethod) {
            $methodsContent .= $this->getInterfaceMethodContent($interfaceMethod);
        }
        return $methodsContent;
    }

    /**
     * @param InterfaceMethod $interfaceMethod метод интерфейса
     * @return string контент методов интерфейса
     */
    protected function getInterfaceMethodContent(InterfaceMethod $interfaceMethod)
    {
        return StringHelper::replacePatterns(
            $this->getTemplateInterfaceMethod(),
            [
                '%methodComments%' => $this->getMethodCommentsContent($interfaceMethod),
                '%methodName%' => $interfaceMethod->getName(),
                '%methodParameters%' => $this->getMethodParametersContent($interfaceMethod),
                '%returnType%' => $this->getReturnTypeContent($interfaceMethod)
            ]
        );
    }

    /**
     * @param InterfaceMethod $interfaceMethod метод интерфейса
     * @return string контент параметров метода
     */
    protected function getMethodParametersContent(InterfaceMethod $interfaceMethod)
    {
        $parameterList = [];
        foreach ($interfaceMethod->getParameterList() as $parameter){
            $parameterList[] = $this->getMethodParameterContent($parameter);
        }
        return implode(", ", $parameterList);
    }

    /**
     * @param InterfaceMethodParameter $parameter параметр метода
     * @return string контент параметра метода
     */
    protected function getMethodParameterContent(InterfaceMethodParameter $parameter)
    {
        return StringHelper::replacePatterns(
            $this->getTemplateMethodParameter(),
            [
                '%parameterType%' => $this->getParameterCommentType($parameter),
                '%parameterName%' => $parameter->getName()
            ]
        );
    }

    /**
     * @param InterfaceMethod $interfaceMethod метод интерфейса
     * @return string контент возвращаемого типа
     */
    protected function getReturnTypeContent(InterfaceMethod $interfaceMethod)
    {
        $returnType = $interfaceMethod->getReturnType();
        $fullReturnType = $interfaceMethod->getReturnFullType();
        $resultType = $this->getType($returnType, $fullReturnType);
        return empty($resultType) === false
            ? ': '. $resultType
            : '';
    }

    /**
     * @param InterfaceMethod $interfaceMethod метод интерфейса
     * @return string контент коментариев метода
     */
    protected function getMethodCommentsContent(InterfaceMethod $interfaceMethod)
    {
        $methodDescription = $this->getMethodDescriptionCommentContent($interfaceMethod);
        $parametersComments = $this->getParametersCommentsContent($interfaceMethod);
        $resultComment = $this->getResultCommentContent($interfaceMethod);
        $comments = [
            $methodDescription,
            $parametersComments,
            $resultComment
        ];
        $notEmptyComments = array_filter($comments);
        return count($notEmptyComments) > 0
            ?   StringHelper::replacePatterns(
                    $this->getTemplateMethodComment(),
                    [
                        '%comments%' => implode("\n", $notEmptyComments)
                    ]
                )
            :   '';
    }

    /**
     * @param InterfaceMethod $interfaceMethod метод интерфейса
     * @return string контент комментария-описания метода
     */
    protected function getMethodDescriptionCommentContent(InterfaceMethod $interfaceMethod)
    {
        $methodComment = $interfaceMethod->getComment();
        return
            empty($methodComment) === false
            ?   StringHelper::replacePatterns(
                    $this->getTemplateDescriptionComment(),
                    [
                        '%description%' => $interfaceMethod->getComment()
                    ]
                )
            :   '';
    }

    /**
     * @param InterfaceMethod $interfaceMethod метод интерфейса
     * @return string контент коментариев параметров метода
     */
    protected function getParametersCommentsContent(InterfaceMethod $interfaceMethod)
    {
        $parametersComments = [];
        foreach ($interfaceMethod->getParameterList() as $parameter)
        {
            $parametersComments[] = $this->getParameterCommentContent($parameter);
        }
        return implode("\n", $parametersComments);
    }

    /**
     * @param InterfaceMethodParameter $parameter параметр метода
     * @return string коментарий параметра
     */
    protected function getParameterCommentContent(InterfaceMethodParameter $parameter)
    {
        return StringHelper::replacePatterns(
            $this->getTemplateParameterComment(),
            [
                '%parameterType%' => $this->getParameterCommentType($parameter),
                '%parameterName%' => $parameter->getName(),
                '%parameterComment%' => $parameter->getComment()
            ]
        );
    }

    /**
     * @param InterfaceMethodParameter $parameter параметр метода интерфейса
     * @return string тип параметра отображаемый в коментарии метода
     */
    protected function getParameterCommentType(InterfaceMethodParameter $parameter)
    {
        $parameterType = $parameter->getType();
        $parameterFullType = $parameter->getFullType();
        $resultType = $this->getType($parameterType, $parameterFullType);
        return empty($resultType)
            ? 'mixed'
            : $resultType;
    }

    /**
     * @param InterfaceMethod $interfaceMethod метод интерфейса
     * @return string коментарий возвращаемого значения
     */
    protected function getResultCommentContent(InterfaceMethod $interfaceMethod)
    {
        $returnFullType = $interfaceMethod->getReturnFullType();
        return empty($returnFullType)
            ? ''
            : StringHelper::replacePatterns(
                $this->getTemplateReturnComment(),
                [
                    '%returnType%' => $this->getType(
                        $interfaceMethod->getReturnFullType(),
                        $interfaceMethod->getReturnType()
                    ),
                    '%returnComment%' => $interfaceMethod->getReturnComment()
                ]
            );
    }


    /**
     * @param string $type тип (без неймспейса)
     * @param string $fullType тип (с неймспейсом)
     * @return string отображаемый тип
     */
    protected function getType(string $type, string $fullType)
    {
        $result = '';
        $useClassList = $this->getUseClassList();
        if(empty($type) === false){
            $result .=
                array_key_exists($type, $useClassList)
                &&
                $fullType === $useClassList[$type]
                    ? $type
                    : $fullType;

        }
        return $result;
    }

    /** генерация кода класса реестра */
    public function generate()
    {
        $interfaceDirectory = $this->getInterfaceDirectoryPath();
        FileHelper::createDirectory($interfaceDirectory);

        file_put_contents(
            $this->getInterfacePath(),
            $this->getInterfaceContent()
        );
    }
}

