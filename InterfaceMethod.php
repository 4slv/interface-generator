<?php

namespace InterfaceGenerator;

/** Интерфейс метода */
class InterfaceMethod
{
    /** @var string название метода */
    protected $name;

    /** @var string возвращаемый тип (с неймспейсом) */
    protected $returnFullType;

    /** @var string коментарий к возвращаемому типу */
    protected $returnComment;

    /** @var InterfaceMethodParameter[] список параметров метода */
    protected $parameterList = [];

    /** @var string коментарий метода */
    protected $comment;

    /**
     * @return string название метода
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name название метода
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string возвращаемый тип (с неймспейсом)
     */
    public function getReturnFullType()
    {
        return $this->returnFullType;
    }

    /**
     * @return string возвращаемый тип (без неймспейса)
     */
    public function getReturnType(): string
    {
        $typeParts = explode('\\', $this->returnFullType);
        return array_pop($typeParts);
    }

    /**
     * @param string $returnFullType возвращаемый тип (с неймспейсом)
     * @return $this
     */
    public function setReturnFullType(string $returnFullType)
    {
        $this->returnFullType = $returnFullType;
        return $this;
    }

    /**
     * @return string коментарий к возвращаемому типу
     */
    public function getReturnComment()
    {
        return $this->returnComment;
    }

    /**
     * @param string $returnComment коментарий к возвращаемому типу
     * @return $this
     */
    public function setReturnComment($returnComment)
    {
        $this->returnComment = $returnComment;
        return $this;
    }

    /**
     * @return InterfaceMethodParameter[] список параметров метода
     */
    public function getParameterList(): array
    {
        return $this->parameterList;
    }

    /**
     * @param InterfaceMethodParameter[] $parameterList список параметров метода
     * @return $this
     */
    public function setParameterList(array $parameterList)
    {
        $this->parameterList = $parameterList;
        return $this;
    }

    /**
     * @return string коментарий метода
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment коментарий метода
     * @return $this
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
        return $this;
    }


}