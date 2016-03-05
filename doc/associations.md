## How to manage associations

Accessible can also manage the relations between your classes. This feature resides in the two following annotation:

- `@Referenced`: Indicates that the associated object (or objects if the property is a collection) has a reference to the current object.
- `@InCollection`: Indicates that the associated object (or objects if the property is a collection) has a collection in which the current object resides.

Here is a simple example of how it can be used:

```php
use Accessible\Annotation\Inverted;

class Student
{
  /**
   * @Access({Access:GET, Access::SET})
   * @Referenced(className=Bag::class, propertyName="owner")
   */
  private $bag;
}

class Bag
{
  /**
   * @Access({Access:GET, Access::SET})
   */
  private $owner;
}

$student = new Student();
$bag = new Bag();

$student->setBag($bag);
$bag->getStudent(); // -> $student
```

In this example, when a new value to `Student#bag`, the property `Bag#owner` of the previous bag will be set to `null`, and to $student in the new bag.

If you add another `@Referenced` annotation in `Bag#owner`, modifying this property will also modify `Student#bag`.

Note that if you have a Many to One or a Many to Many relationship, the properties defined as collections must have a `@ListBehavior` or a `@SetBehavior` annotation.

### Types of associations

This section gives examples of how you can manage the relations between your classes, following the relationship vocabulary you can find with Doctrine.

#### One to One relationship

In this example, a student has a bag, and a bag belongs to a student.

```php
use Accessible\Annotation\Inverted;

class Student
{
  /**
   * @Access({Access:GET, Access::SET})
   * @Referenced(className=Bag::class, propertyName="owner")
   */
  private $bag;
}

class Bag
{
  /**
   * @Access({Access:GET, Access::SET})
   * @Referenced(className=Student::class, propertyName="bag")
   */
  private $owner;
}

$student = new Student();
$bag = new Bag();

$student->setBag($bag);
$bag->getOwner(); // -> $student

$bag->setOwner(new Student());
$student->getBag(); // -> null
```

#### One to Many / Many to One relationship

In this example, a student has several books, and a book belongs to a student.

```php
use Accessible\Annotation\Inverted;
use Accessible\Annotation\Mapped;

class Student
{
  /**
   * @Access({Access:GET, Access::SET})
   * @ListBehavior
   * @Referenced(className=Book::class, propertyName="owner")
   * @Initialize({})
   */
  private $books;
}

class Book
{
  /**
   * @Access({Access:GET, Access::SET})
   * @InCollection(className=Student::class, propertyName="books")
   */
  private $owner;
}

$student = new Student();
$book = new Book();

$student->addBook($book);
$book->getOwner(); // -> $student

$book->setOwner(new Student());
$student->getBag(); // -> []
```

#### Many to Many relationship

In this example, a student has several teachers, and a teacher teaches to several students.

```php
use Accessible\Annotation\Mapped;

class Student
{
  /**
   * @Access({Access:GET, Access::SET})
   * @SetBehavior
   * @InCollection(className=Teacher::class, propertyName="students")
   * @Initialize({})
   */
  private $teachers;
}

class Teacher
{
  /**
   * @Access({Access:GET, Access::SET})
   * @InCollection(className=Student::class, propertyName="teachers")
   */
  private $students;
}

$student = new Student();
$teacher = new Teacher();

$student->addTeacher($teacher);
$teacher->getStudents(); // -> [$student]

$teacher->removeStudent($student);
$student->getTeachers(); // -> []
```

### Update an associated object manually

If you want to manage your class associations manually, you can use the `updatePropertyAssociation()` method. Here is an example of how to use it:

```php
class Student
{
  /**
   * @Access({Access:GET, Access::SET})
   * @Referenced(className=Bag::class, propertyName="owner")
   */
  private $bag;

  public function setBag(Bag $bag)
  {
    $valuesToUpdate = [
      'oldValue' => $this->bag,
      'newValue' => $bag
    ];
    $this->bag = $bag;
    $this->updatePropertyAssociation('bag', $valuesToUpdate);
  }
}
```

Note that `$valuesToUpdate` could also only be composed of one of these two values (and could even be empty).
