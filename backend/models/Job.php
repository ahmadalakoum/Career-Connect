<?php

class Job
{
    private int $id;
    private string $employer_id;
    private string $title;
    private string $description;
    private string $salary;
    private string $category;
    private string $location;
    private string $deadline;

    // Constructor to initialize attributes
    public function __construct(
        int $id,
        string $employer_id,
        string $title,
        string $description,
        string $salary,
        string $category,
        string $location,
        string $deadline
    ) {
        $this->id = $id;
        $this->employer_id = $employer_id;
        $this->title = $title;
        $this->description = $description;
        $this->salary = $salary;
        $this->category = $category;
        $this->location = $location;
        $this->deadline = $deadline;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getEmployerId(): string
    {
        return $this->employer_id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSalary(): string
    {
        return $this->salary;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getDeadline(): string
    {
        return $this->deadline;
    }

    // Setters
    public function setEmployerId(string $employer_id): void
    {
        $this->employer_id = $employer_id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setSalary(string $salary): void
    {
        $this->salary = $salary;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }

    public function setDeadline(string $deadline): void
    {
        $this->deadline = $deadline;
    }
}
