# Proton CRUD

[![Latest Version](https://img.shields.io/github/release/indigophp/proton-crud.svg?style=flat-square)](https://github.com/indigophp/proton-crud/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/indigophp/proton-crud.svg?style=flat-square)](https://travis-ci.org/indigophp/proton-crud)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/indigophp/proton-crud.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/proton-crud)
[![Quality Score](https://img.shields.io/scrutinizer/g/indigophp/proton-crud.svg?style=flat-square)](https://scrutinizer-ci.com/g/indigophp/proton-crud)
[![HHVM Status](https://img.shields.io/hhvm/indigophp/proton-crud.svg?style=flat-square)](http://hhvm.h4cc.de/package/indigophp/proton-crud)
[![Total Downloads](https://img.shields.io/packagist/dt/indigophp/proton-crud.svg?style=flat-square)](https://packagist.org/packages/indigophp/proton-crud)

**Proton CRUD skeleton.**


## Install

Via Composer

``` bash
$ composer require indigophp/proton-crud
```


## Usage


### Form Transformers

The following explanation is from [Symfony Form Component](https://github.com/symfony/Form/blob/master/Form.php).


> To implement your own form fields, you need to have a thorough understanding
of the data flow within a form. A form stores its data in three different
representations:

> (1) the "model" format required by the form's object
  (2) the "normalized" format for internal processing
  (3) the "view" format used for display

> A date field, for example, may store a date as "Y-m-d" string (1) in the
object. To facilitate processing in the field, this value is normalized
to a DateTime object (2). In the HTML representation of your form, a
localized string (3) is presented to and modified by the user.

> In most cases, format (1) and format (2) will be the same. For example,
a checkbox field uses a Boolean value for both internal processing and
storage in the object. In these cases you simply need to set a value
transformer to convert between formats (2) and (3). You can do this by
calling addViewTransformer().

> In some cases though it makes sense to make format (1) configurable. To
demonstrate this, let's extend our above date field to store the value
either as "Y-m-d" string or as timestamp. Internally we still want to
use a DateTime object for processing. To convert the data from string/integer
to DateTime you can set a normalization transformer by calling
addNormTransformer(). The normalized data is then converted to the displayed
data as described before.

> The conversions (1) -> (2) -> (3) use the transform methods of the transformers.
The conversions (3) -> (2) -> (1) use the reverseTransform methods of the transformers.


In our case (1) -> (2) and (2) -> (1) conversions are done by Doctrine itself. The rest ((2) -> (3), (3) -> (2)) is done by form transformers:

``` php
use Proton\Crud\FormTransformer;
use Fractal\Resource\Item;

class ExampleTransformer extends FormTransformer
{
	/**
	 * {@inheritdoc}
	 */
	public function transformToInternal(array $entity)
	{
		$entity['dateTimeValue'] = \DateTime::createFromFormat('format', $entity['dateTimeValue']);

		return $entity;
	}

	/**
	 * {@inheritdoc}
	 */
	public function transformToDisplay(array $entity)
	{
		$entity['dateTimeValue'] = $entity['dateTimeValue']->format('SOME_FORMAT_STRING');

		return $entity;
	}
}

$extractedData = [
	'dateTimeValue' => new \DateTime('now');
];

$transformer = new ExampleTransformer(FormTransformer::TO_DISPLAY);
$resource = new Item($extractedData, $transformer);
```

Form transformers are very similar to hydrators, but they SHOULD NOT modify any data in entities. In fact, transformers should not get an instance of entity, rather an array of data extracted from an entity;


## Testing

``` bash
$ phpunit
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [Márk Sági-Kazár](https://github.com/sagikazarmark)
- [All Contributors](https://github.com/indigophp/proton-crud/contributors)


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
