## How to manage collections

Accessible provides 3 annotations to give to make properties behave as collections:

- `ListBehavior`: The property behaves as a simple list.
- `SetBehavior`: The property behaves as a set (every item is unique).
- `MapBehavior`: The property behaves as a key => value array.

These annotations add two methods to the class:

- `addX`: Add an item to the collection.
- `removeX`: Remove an item from the collection.

The 'X' in these methods is the name of an item in the collection. The item name may be defined in the annotation by setting its `itemName` property. By default, the item name will be the singular version of the property.

Here is an example:

```php
use Accessible\Annotation\ListBehavior;
use Accessible\Annotation\MapBehavior;
use Accessible\Annotation\SetBehavior;

class Student
{
  /**
   * This property is initialized as an empty array and can be modified with addCourse() and removeCourse().
   *
   * @Access({Access:GET})
   * @SetBehavior
   * @Initialize({})
   */
  private $courses;

  /**
   * This property is initialized as an empty array and can be modified with addTask() and removeTask().
   *
   * @Access({Access:GET})
   * @ListBehavior(itemName="task")
   * @Initialize({})
   */
  private $homework;

  /**
   * This property is initialized as an empty array and can be modified with addNote() and removeNote().
   *
   * @Access({Access:GET})
   * @MapBehavior()
   * @Initialize({})
   */
  private $notes;
}

$student = new Student();

$course = new Course();
$student->addCourse($course);
$student->getCourses(); // -> [$course]
$student->removeCourse($course);

$task = new Task();
$student->addTask($task);
$student->getHomework(); // -> [$task]
$student->removeTask($task);

$note = new Note();
$student->addNote("someNote", $note);
$student->getNotes(); // -> ["someNote" => $note]
$student->removeNote("someNote");
```

Note that the properties are initialized as empty arrays, but they can also be initialized as instances of Doctrine collections via `@InitializeObject`.
