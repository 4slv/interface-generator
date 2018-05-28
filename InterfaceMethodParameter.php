<?php

namespace InterfaceGenerator;

/** Параметр метода интерфейса */
class InterfaceMethodParameter
{
    /** @var string название параметра метода */
    protected $name;

    /** @var string тип параметра метода (с неймспейсом) */
    protected $fullType;

    /** @var string комментарий параметра метода */
    protected $comment;

    /**
     * @return string название параметра метода
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name название параметра метода
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string тип параметра метода (с неймспейсом)
     */
    public function getFullType()
    {
        return $this->fullType;
    }

    /**
     * @param string $fullType тип параметра метода (с неймспейсом)
     * @return $this
     */
    public function setFullType(string $fullType)
    {
        $this->fullType = $fullType;
        return $this;
    }

    /**
     * @return string тип (без неймспейса)
     */
    public function getType(): string
    {
        $typeParts = explode('\\', $this->fullType);
        return array_pop($typeParts);
    }

    /**
     * @return string комментарий параметра метода
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment комментарий параметра метода
     * @return $this
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
        return $this;
    }

}