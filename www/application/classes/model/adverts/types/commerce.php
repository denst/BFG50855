<? // Коммерческая недвижиимость
class Model_Adverts_Types_Commerce extends ORM {
	protected $_table_name = 'adverts_types_commerces';
	protected $_belongs_to = array(
		'advert' => array(
            'model' => 'advert',
            'foreign_key' => 'adverts_id',
        ),
		'type' => array(
            'model' => 'adverts_types_commerce_type',
            'foreign_key' => 'commerce_types_id',
        ),
		'property' => array(
            'model' => 'adverts_types_commerce_property',
            'foreign_key' => 'properties_types_id',
        ),
    );
}