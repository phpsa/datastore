<?php

namespace Phpsa\Datastore;

use Phpsa\Datastore\Models\Datastore as DatastoreModel;
use Phpsa\Datastore\DatastoreException;
use Phpsa\Datastore\Asset;
use Phpsa\Datastore\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Validator;
use ReflectionClass;
use ReflectionProperty;

class Datastore{

	protected $__asset				 = false;
	protected $__asset_properties	 = false;
	protected $__value_equals		 = false;
	protected $__status_equals		 = false;
	protected $__start_date			 = null;
	protected $__end_date			 = null;


	/**
	 *
	 * @var DatastoreModel
	 */
	protected $__model;

	/**
	 *
	 * @var array of datastore_model
	 */
	public $ownDatastore = false;

	/**
	 * The current record id
	 * @var record id
	 */
	public $id = 0;

	/**
	 * construct a new datastore instance
	 * if an id is passed populate the data
	 * @param int $id
	 */
	public function __construct($id = 0)
	{

		$this->id = $id;
		if ($id)
		{
			$this->__model = DatastoreModel::findOrFail($id);

			if(!$this->__model){
				//could not load <div class="">thr</div>
				throw new DatastoreException("Record not found");
			}

			foreach ($this->__model->toArray() as $k => $v)
			{
				if($k === 'options'){
					$v = json_decode($v, true);
				}
				$this->{$k} = $v;
			}


			$stores = $this->__model->properties;
			if ($stores)
			{
				foreach ($stores as $ds)
				{
					$this->ownDatastore[] = self::get($ds->id);
				}
			}
		}else{
			$this->__model = new DatastoreModel();
		}
	}

	/**
	 * Dispense a new asset
	 *
	 * @param Phpsa\Datastore\Asset and instance of
	 *
	 * @return void
	 * @author Craig Smith <craig.smith@customd.com>
	 */
	public static function dispense($type){
		$datastore = new self();
		$datastore->setAssetType($type);
		return $datastore;
	}

	public static function get($id){
		$datastore = new self($id);
		$datastore->setAssetType($datastore->type);
		return $datastore;
	}

	/**
	 * Gets a new asset instance
	 * @param string $type
	 * @param int $id
	 * @return \DF_Datastore
	 */
	public static function getAsset($type, $id = false)
	{
		$ds = new self($id);
		$ds->setAssetType($type);
		return $ds;
	}


	public function ownDatastore(){
		return ($this->ownDatastore)?$this->ownDatastore: array();
	}

	/**
	 * Sets the type of asset & maps identifier keys
	 * @param string $type
	 * @return DF_Datastore
	 */
	public function setAssetType($type)
	{

		$this->__asset = Asset::factory($type);

		foreach ($this->__asset as $k => $v)
		{
			if ($k != 'properties')
			{
				if ($k == 'value_equals')
				{
					$this->__value_equals = $v;
				}
				else if ($k == 'status_equals')
				{
					$this->__status_equals = $v;
				}
				else if ($k == 'start_date')
				{
					$this->__start_date = $v;
				}
				else if ($k == 'end_date')
				{
					$this->__end_date = $v;
				}
				else
				{
					$this->$k = (isset($this->$k)) ? $this->$k : $v;
				}
			}
		}
		$this->type = $type;

		$this->module = Asset::get_module($type);

		$this->setInternals();

		return $this;
	}


	/**
	 * Sets the internal mapping
	 * @todo - pehaps this should become protected ?
	 */
	public function setInternals()
	{

		foreach ($this->__asset->properties as $p => $v)
		{

			if (!isset($this->__asset_properties[$p]))
			{
				$this->__asset_properties[$p]['temp']					 = self::getAsset($this->__asset->properties[$p]['type']);
				$this->__asset_properties[$p]['temp']->name				 = (isset($this->__asset->properties[$p]['name'])) ? $this->__asset->properties[$p]['name'] : null;
				$this->__asset_properties[$p]['temp']->key				 = $p;
				$this->__asset_properties[$p]['temp']->private			 = (isset($this->__asset->properties[$p]['private'])) ? $this->__asset->properties[$p]['private'] : false; // accessibility
				$this->__asset_properties[$p]['temp']->value			 = (isset($this->__asset->properties[$p]['value'])) ? $this->__asset->properties[$p]['value'] : null; // the actual value of the property
				$this->__asset_properties[$p]['temp']->options			 = (isset($this->__asset->properties[$p]['options'])) ? $this->__asset->properties[$p]['options'] : null; // additional options specific to the property
				$this->__asset_properties[$p]['temp']->callback			 = (isset($this->__asset->properties[$p]['callback'])) ? $this->__asset->properties[$p]['callback'] : null; // additional options specific to the property
				$this->__asset_properties[$p]['temp']->meta				 = (isset($this->__asset->properties[$p]['meta'])) ? $this->__asset->properties[$p]['meta'] : null; // additional options specific to the property
				$this->__asset_properties[$p]['temp']->help				 = (isset($this->__asset->properties[$p]['help'])) ? $this->__asset->properties[$p]['help'] : null; // additional options specific to the property
				$this->__asset_properties[$p]['temp']->required			 = (isset($this->__asset->properties[$p]['required'])) ? $this->__asset->properties[$p]['required'] : true; // is the property required
				$this->__asset_properties[$p]['temp']->limit			 = (isset($this->__asset->properties[$p]['limit'])) ? $this->__asset->properties[$p]['limit'] : null; // additional options specific to the property
				$this->__asset_properties[$p]['temp']->warning			 = (isset($this->__asset->properties[$p]['warning'])) ? $this->__asset->properties[$p]['warning'] : null; // additional options specific to the property
				$this->__asset_properties[$p]['temp']->max_instances	 = (isset($this->__asset->properties[$p]['max_instances'])) ? $this->__asset->properties[$p]['max_instances'] : 0; // there can be only one
			}
		}

		// fitst check whether we have any datastore set
		if ($this->ownDatastore)
		{

			// if it has a datastore, its easy - map the buggers
			$this->key = self::getKey($this->type);

			foreach ($this->ownDatastore as $idx => $each)
			{
				// create a map to our property bean
				if ($each->namespace == 'property')
				{
					$this->__asset_properties[$each->key]	 = array('temp' => $each);
					@$this->ownDatastore[$idx]->required		 = (isset($this->__asset->properties[$each->key]['required'])) ? $this->__asset->properties[$each->key]['required'] : true;
					@$this->ownDatastore[$idx]->limit		 = (isset($this->__asset->properties[$each->key]['limit'])) ? $this->__asset->properties[$each->key]['limit'] : null;
					@$this->ownDatastore[$idx]->warning		 = (isset($this->__asset->properties[$each->key]['warning'])) ? $this->__asset->properties[$each->key]['warning'] : null;
					@$this->ownDatastore[$idx]->help			 = (isset($this->__asset->properties[$each->key]['help'])) ? $this->__asset->properties[$each->key]['help'] : null;
					@$this->ownDatastore[$idx]->max_instances = (isset($this->__asset->properties[$each->key]['max_instances'])) ? $this->__asset->properties[$each->key]['max_instances'] : 0;
					@$this->ownDatastore[$idx]->options = (isset($this->__asset->properties[$each->key]['options'])) ? $this->__asset->properties[$each->key]['options'] : null;
				}
			}
			//echo '<pre>$this->ownDatastore: '; print_r($this->ownDatastore); echo '</pre>'; die();
		}
		else
		{
			// nope - net to create some datastore beans to represent our properties
			foreach ($this->__asset->properties as $prop => $data)
			{

				// we need to create a temporary new bean to store new data
				$this->__asset_properties[$prop]['temp']				 = self::getAsset($this->__asset->properties[$prop]['type']);
				$this->__asset_properties[$prop]['temp']->name			 = (isset($this->__asset->properties[$prop]['name'])) ? $this->__asset->properties[$prop]['name'] : null;
				$this->__asset_properties[$prop]['temp']->key			 = $prop;
				$this->__asset_properties[$prop]['temp']->private		 = (isset($this->__asset->properties[$prop]['private'])) ? $this->__asset->properties[$prop]['private'] : false; // accessibility
				$this->__asset_properties[$prop]['temp']->value			 = (isset($this->__asset->properties[$prop]['value'])) ? $this->__asset->properties[$prop]['value'] : null; // the actual value of the property
				$this->__asset_properties[$prop]['temp']->options		 = (isset($this->__asset->properties[$prop]['options'])) ? $this->__asset->properties[$prop]['options'] : null; // additional options specific to the property
				$this->__asset_properties[$prop]['temp']->callback		 = (isset($this->__asset->properties[$prop]['callback'])) ? $this->__asset->properties[$prop]['callback'] : null; // additional options specific to the property
				$this->__asset_properties[$prop]['temp']->meta			 = (isset($this->__asset->properties[$prop]['meta'])) ? $this->__asset->properties[$prop]['meta'] : null; // additional options specific to the property
				$this->__asset_properties[$prop]['temp']->help			 = (isset($this->__asset->properties[$prop]['help'])) ? $this->__asset->properties[$prop]['help'] : null; // additional options specific to the property
				$this->__asset_properties[$prop]['temp']->required		 = (isset($this->__asset->properties[$prop]['required'])) ? $this->__asset->properties[$prop]['required'] : true; // is the property required
				$this->__asset_properties[$prop]['temp']->limit			 = (isset($this->__asset->properties[$prop]['limit'])) ? $this->__asset->properties[$prop]['limit'] : null; // additional options specific to the property
				$this->__asset_properties[$prop]['temp']->warning		 = (isset($this->__asset->properties[$prop]['warning'])) ? $this->__asset->properties[$prop]['warning'] : null; // additional options specific to the property
				$this->__asset_properties[$prop]['temp']->max_instances	 = (isset($this->__asset->properties[$prop]['max_instances'])) ? $this->__asset->properties[$prop]['max_instances'] : null; // there can be only one

			}
		}
	}



	/**
	 * Gets the Asset Key
	 * @param string $string
	 * @return string
	 */
	public static function getKey($string)
	{
		$new	 = Asset::_splitByCaps($string);
		$explode = explode(' ', $new);

		if (isset($explode[0]) && $explode[0] == 'Ams')
		{
			// drop the ams portion
			array_shift($explode);
		}
		// drop the last part too
		$last = array_pop($explode);
		//lowercase the first part
		if (isset($explode[0]))
		{
			$explode[0] = strtolower($explode[0]);
		}

		return (count($explode) > 1) ? implode('', $explode) : $explode[0];
	}

	/**
	 * Setter / getter of asset property value,
	 * pass 1 param to get the value,
	 * pass 2 params to set the value
	 * @return mixed
	 * @throws Exception
	 */
	public function prop()
	{
		$num_args = func_num_args();

		// one arg, return prop by key
		// two args set prop by name to arg2
		switch ($num_args)
		{
			case 1:
				$prop = func_get_arg(0);
				if (isset($this->__asset_properties[$prop]))
				{
					// check whether this is new or existibng
					if (isset($this->__asset_properties[$prop]['temp']))
					{
						return $this->__asset_properties[$prop]['temp']->value;
					}
					else
					{
						return $this->ownDatastore[$this->__asset_properties[$prop]]->value;
					}
				}
				else
				{
					return false;
				}
				break;

			case 2:
				$prop	 = func_get_arg(0);
				$val	 = func_get_arg(1);
				if (isset($this->__asset_properties[$prop]))
				{
					// check whether this is new or existing
					if (isset($this->__asset_properties[$prop]['temp']))
					{
						$this->__asset_properties[$prop]['temp']->value = $val;
						// if value equals is set, set it too

						if ($this->__value_equals && $this->__value_equals == $prop)
						{
							$this->val(Asset::callStatic($this->type, 'valueEquals', array($val)));
						}
						else if ($this->__status_equals && $this->__status_equals == $prop)
						{
							$this->status($val);
						}
						else if ($this->__start_date && $this->__start_date == $prop)
						{
							$this->start_date($val);
						}
						else if ($this->__end_date && $this->__end_date == $prop)
						{
							$this->end_date($val);
						}
					}
					else
					{
						$this->ownDatastore[$this->__asset_properties[$prop]]->value = $val;
						// if value equals is set, set it too
						if ($this->__value_equals && $this->__value_equals == $prop)
						{
							$this->val(Asset::callStatic($this->type, 'valueEquals', array($val)));
						}
						else if ($this->__status_equals && $this->__status_equals == $prop)
						{
							$this->status($val);
						}
						else if ($this->__start_date && $this->__start_date == $prop)
						{
							$this->start_date($val);
						}
						else if ($this->__end_date && $this->__end_date == $prop)
						{
							$this->end_date($val);
						}
					}
					return $this;
				}
				else
				{
					throw new DatastoreException('The property "' . $prop . '" is not defined for the "' . $this->type . '" asset');
				}
				break;

			default:
				throw new DatastoreException('DF_Datastore::prop accepts either a property name, or a property and value');
		}
	}

	/**
	 *  shortcut to write to the asset's value property
	 * @return mixed
	 */
	public function val()
	{
		$num_args = func_num_args();

		switch ($num_args)
		{
			case 0:
				// return all props as beans
				return $this->value;
				break;

			default:
				$val		 = func_get_arg(0);
				$this->value = $val;
		}
	}

	public function __toString()
	{

		return (string) $this->val();
	}

	/**
	 * Stores our asset
	 * @param DF_Datastore $asset
	 * @param Int $pid Parent Datastore id
	 */
	protected function __store($asset, $pid = null)
	{

		$class	 = new ReflectionClass($asset->__asset);
		$props	 = $class->getProperties(ReflectionProperty::IS_PUBLIC);


		$record = $asset->__model;
		$record->datastore_id = $pid;


		foreach ($props as $prop)
		{
			if (!in_array($prop->name, array('status_equals', 'properties','help','value_equals')))
			{
				$record->{$prop->name} = isset($asset->{$prop->name}) ? $asset->{$prop->name} : NULL;
			}
		}

		if ($asset->__status_equals)
		{
			$record->status = isset($record->{$asset->__status_equals})? $record->{$asset->__status_equals} : $this->prop($this->__status_equals);
		}

		if ($asset->__start_date)
		{
			$record->start_date = isset($record->{$asset->__start_date})? $record->{$asset->__start_date} : $this->prop($this->__start_date);
		}
		if ($asset->__end_date)
		{
			$record->end_date = isset($record->{$asset->__end_date})? $record->{$asset->__end_date} : $this->prop($this->__end_date);
		}

		if( ! empty($record->options) && is_array($record->options))
		{
			$record->options = json_encode($record->options);
		}

		$record->save();


		if ($asset->__asset_properties)
		{
			foreach ($asset->__asset_properties as $prop)
			{
				$this->__store($prop['temp'], $record->id);
			}
		}
		return $record->id;
	}

	/**
	 * Stores the asset
	 * @return int Datastore id
	 */
	public function store($parent = null)
	{

		return $this->__store($this, $parent);
	}

	/**
	 * Echos out the asset markup
	 * @param string $prop
	 * @param string $template
	 */
	public function render($prop, $template = false)
	{
		if (isset($this->__asset_properties[$prop]['temp']))
		{
			echo $this->__asset_properties[$prop]['temp']->getMarkup($template);
		}
		else
		{
			if (isset($this->__asset_properties[$prop]))
			{
				echo $this->ownDatastore[$this->__asset_properties[$prop]]->getMarkup($template);
			}
		}
	}

	/**
	 * Gets the current Datastore markup
	 * @param string $template
	 * @return string
	 */
	public function getMarkup($template = false)
	{

		$vars				 = $this->export();
		$vars['_unique_id']	 = uniqid();


		// we also want to know what asset this property belongs to
		$output = Asset::callStatic($this->type, 'render', array($vars, $template));
		if (!$output)
		{
			return null;
		}
		return $output;
	}


	/**
	 * exports current Datastore to an array
	 * @return array
	 */
	public function export()
	{

		$class	 = new ReflectionClass($this->__asset);
		$props	 = $class->getProperties(ReflectionProperty::IS_PUBLIC);



		foreach ($props as $prop)
		{

			$data[$prop->name] = isset($this->{$prop->name}) ? $this->{$prop->name} : NULL;
		}

		if ($this->__status_equals)
		{

			$data['status'] = isset($data[$this->__status_equals])? $data[$this->__status_equals] : $this->prop($this->__status_equals);
		}
		if ($this->__start_date)
		{
			$data['start_date'] = $data[$this->__start_date];
		}
		if ($this->__end_date)
		{
			$data['end_date'] = $data[$this->__end_date];
		}

		$data['id'] = $this->id;

		return $data;
	}


	/**
	 * Gets form element for a propery
	 * @param string $prop
	 * @param string $template
	 */
	public function form($prop, $template = false)
	{
		if (isset($this->__asset_properties[$prop]['temp']))
		{
			echo $this->__asset_properties[$prop]['temp']->getForm($template, $prop);
		}
		else
		{
			if (isset($this->__asset_properties[$prop]))
			{
				echo $this->ownDatastore[$this->__asset_properties[$prop]]->getForm($template, $prop);
			}
		}
	}

	/**
	 * Gets property form markup
	 * @param string $prop
	 * @param string $template
	 * @param array $_prop
	 * @return type
	 */
	public function returnForm($prop, $template = false, $_prop)
	{

		if (isset($this->__asset_properties[$prop]['temp']))
		{
			return $this->__asset_properties[$prop]['temp']->getForm($template, $_prop);
		}
		else
		{
			if (isset($this->__asset_properties[$prop]))
			{

				return $this->ownDatastore[$this->__asset_properties[$prop]]->getForm($template, $_prop);
			}
		}
	}

	public function getFieldValues(){
		$data = [];
		foreach(array_keys($this->__asset->properties) as $key){
			$data[$key] = $this->prop($key);
		}
		return $data;
	}


	/**
	 * Gets the form
	 * @param string $template
	 * @param array $_prop
	 * @return string
	 */
	public function getForm($template = false, $_prop = array())
	{

		$output = null;
		if ($this->namespace == 'asset')
		{


			foreach ($this->__asset_properties as $prop => $val)
			{
				$output .= $this->returnForm($prop, false, $_prop);
			}

			//  exit;
		}
		else
		{
			return $this->propGetForm($template, $_prop);
		}

		if (!$output)
		{
			return null;
		}
		return $output;
	}

	/**
	 * Gets property form
	 * @param string $template
	 * @param array $_prop
	 * @return string
	 */
	public function propGetForm($template = false, $_prop = array())
	{
		$vars = $this->export();

		if (isset($_prop['unique_id']))
		{
			$vars['_unique_id'] = $_prop['unique_id'];
		}
		else
		{
			$vars['_unique_id'] = md5(uniqid(rand(), true)); //UUID ??
		}


		// we also want to know what asset this property belongs to
		$output = Asset::callStatic($this->type, 'form', array($vars, $template));
		if (!$output)
		{
			return null;
		}
		return $output;
	}

	/**
	 * Injects a form into an asset
	 * Children asset instances
	 * @param string $uid
	 * @return string
	 */
	public function injectForm($uid)
	{

		$output = null;
		if ($uid)
		{
			$unique_id = md5($uid);
		}
		else
		{
			$unique_id = md5(uniqid(rand(), true));
		}


		foreach ($this->__asset_properties as $prop => $each)
		{
			if (isset($this->__asset_properties[$prop]['temp']))
			{
				$xvars				 = $this->__asset_properties[$prop]['temp']->export();
				$xvars['unique_id']	 = $unique_id;

				$output .= Asset::callStatic($this->__asset_properties[$prop]['temp']->type, 'form', array($xvars, $this->type));
			}
			else
			{
				if (isset($this->__asset_properties[$prop]))
				{
					$xvars				 = $this->ownDatastore[$this->__asset_properties[$prop]]->export();
					$xvars['unique_id']	 = $unique_id;

					$output .= Asset::callStatic($this->ownDatastore[$this->__asset_properties[$prop]]->type, 'form', array($xvars, $this->type));
				}
			}
		}

		// we also want to know what asset this property belongs to
		$output .= '<input type="hidden" class="asset-injector-input" data-type="' . $this->type . '" id="' . $unique_id . '" name="assetInjectionform[' . $this->type . '][' . $unique_id . '][id]" value="' . $this->id . '" />';
		if (!$output)
		{
			return null;
		}
		return $output;
	}


	/**
	 *  gets the form necessary for building the meta data
	 * @return string
	 */
	public function getMetadataForm()
	{
		$output = null;
		// must be a parent asset
		if ($this->namespace == 'asset' && !$this->is_child)
		{
			if ($this->meta_description !== 'off')
			{
				$data = array(
					'name'		 => 'Meta-Description',
					'key'		 => 'meta_description',
					'help'	 => 'You can associate a Meta-Description with this content. No more than 155 characters is considered optimal',
					'value'		 => $this->meta_description
				);

				$output .= Asset::callStatic(ASSET::METATEXT, 'form', array($data, 'metatext'));
			}

			if ($this->meta_keywords !== 'off' /* &&  CONFIG->get('settings.allow_meta_keywords' ) */)
			{
				$data = array(
					'name'		 => 'Meta-Keywords',
					'key'		 => 'meta_keywords',
					'help'	 => 'You can associate Meta-Keywords with this content, with each keyword or key-phrase being seperated by a comma. <br/><b>Note :</b> There is evidence that suggests Keywords can hurt your SEO Ranking',
					'value'		 => $this->meta_keywords
				);

				$output .= Asset::callStatic(ASSET::METATEXT, 'form', array($data, 'metatext'));
			}
		}
		if (!$output)
		{
			return null;
		}
		return $output;
	}

	/**
	 *  gets the form necessary for building the styling and behaviour data
	 * @return string
	 */
	public function getDeveloperForm()
	{
		$output = null;
		// must be a parent asset
		if ($this->namespace == 'asset' && !$this->is_child)
		{
			if ($this->page_css !== 'off')
			{
				$data = array(
					'name'		 => 'Custom CSS',
					'key'		 => 'page_css',
					'help'	 	 => 'Custom css rules for this page display', // 'You can associate a Meta-Description with this content. No more than 155 characters is considered optimal',
					'value'		 => $this->page_css
				);

				$output .= Asset::callStatic(ASSET::METATEXT, 'form', array($data, 'metatext'));
			}

			if ($this->page_js !== 'off' )
			{
				$data = array(
					'name'		 => 'Custom JS',
					'key'		 => 'page_js',
					'help'	 	 => 'custom javascript for this page display', // 'You can associate Meta-Keywords with this content, with each keyword or key-phrase being seperated by a comma. <br/><b>Note :</b> There is evidence that suggests Keywords can hurt your SEO Ranking',
					'value'		 => $this->page_js
				);

				$output .= Asset::callStatic(ASSET::METATEXT, 'form', array($data, 'metatext'));
			}
		}
		if (!$output)
		{
			return null;
		}
		return $output;
	}


	/**
	 * Validates the current form
	 * @return boolean if the form is valid or not
	 */
	public function validate($request = null)
	{

		$config = array();
		$messages = array();
		foreach ($this->__asset->properties as $key => $property)
		{
			if(empty($property['required']) || $property['required'] === true)
			{
				$rules = empty($property['validation_rules']) ? 'required' : $property['validation_rules'];
				if(!empty($property['validation_messages'])){
					if(is_string($property['validation_messages'])){
						$messages[$key . '.required'] = $property['validation_messages'];
					}
				}
				$config[$key] = $rules;
			}

		}

//@TODO MAP TO: https://laravel.com/docs/5.4/validation
		if ($config)
		{
			$validator = Validator::make($request, $config, $messages);
			if($validator->fails()){
				throw new ValidationException($validator);
			}
		}
		return true;
	}

	/**
	 * gets current datastore id
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Gets the current datastore children
	 * @return array
	 */
	public function getChildren()
	{
		$return = false;

		if ($this->ownDatastore)
		{
			foreach ($this->ownDatastore as $child => $child_bean)
			{
				if ($child_bean->namespace == 'asset')
				{
					$return[] = $child_bean;
				}
			}
		}
		return $return;
	}

	/**
	 * Retrieves available assets
	 * @param boolean $grouped
	 * @return array
	 * @TODO :: Need a better way of handling these
	 * @Most likely from an config in the datastore config fodler / database table with datastore types!?
	 */
	public static function findAssets($grouped = false)
	{
		/*get_instance()->load->helper('file_helper');
		$active	 = array();
		$modules = get_instance()->config->item('modules');

		$paths = get_instance()->load->get_package_paths();
		*/
		$paths = [];
		foreach ($paths as $path)
		{
			$files = glob_recursive($path . 'asset/*');
			if ($files)
			{
				foreach ($files as $asset)
				{
					$classname = self::getClassnameFromPath($asset);
					if (!$classname)
					{
						continue;
					}

					if (Asset::assetNamespace($classname) == 'asset')
					{

						$asset = array(
							'class'			 => $classname,
							'name'			 => Asset::assetInfo($classname, 'name'),
							'name_singular'	 => Asset::assetInfo($classname, 'name_singular'),
							'shortname'	     => Asset::assetInfo($classname, 'shortname'),
							'icon'			 => Asset::assetInfo($classname, 'icon'),
							'children'		 => Asset::assetInfo($classname, 'children'),
							'is_child'		 => Asset::assetInfo($classname, 'is_child'),
							'max_instances'	 => Asset::assetInfo($classname, 'max_instances'),
							'about'			 => Asset::callStatic($classname, 'about')
						);



						if ($grouped)
						{
							$mod			 = Asset::get_module($classname);
							$assets[$mod][]	 = $asset;
						}
						else
						{
							$assets[] = $asset;
						}
					}
				}
			}
		}
		return $assets;
	}


	/**
	 * gets the classname from the path of the file
	 * @param string $file
	 * @return string
	 */
	protected static function getClassnameFromPath($file)
	{
		if (!is_file(FCPATH . $file))
		{
			return false;
		}
		$fp = fopen(FCPATH . $file, 'r');

		$class	 = $buffer	 = '';
		$i		 = 0;
		while (!$class)
		{
			if (feof($fp))
				break;

			$buffer	 .= fread($fp, 512);
			$tokens	 = token_get_all($buffer);

			if (strpos($buffer, '{') === false)
				continue;

			for (; $i < count($tokens); $i++)
			{
				if ($tokens[$i][0] === T_CLASS)
				{
					for ($j = $i + 1; $j < count($tokens); $j++)
					{
						if ($tokens[$j] === '{')
						{
							$class = $tokens[$i + 2][1];
						}
					}
				}
			}
		}
		return $class;
	}


	/**
	 * does this datastore have this property?
	 * @param string $prop
	 * @return boolean
	 */
	public function propExists($prop)
	{
		if (isset($this->__asset_properties[$prop]))
		{
			return true;
		}
		return false;
	}


	/**
	 * Populates the current Datastore with data passedin array
	 * @param array $post_data
	 * @return $this
	 */
	public function populateAsset($post_data = array())
	{

		foreach ($post_data as $k => $v)
		{
			switch ($k)
			{
				case 'meta_description':
				case 'meta_keywords':
					$this->$k = strip_tags($v);
					break;

				case 'page_css':
				case 'page_js':
					$this->$k = $v;
					break;

				case '_meta_':
					foreach ($v as $pr => $pv)
					{
						if ($this->propExists($pr))
						{
							if (isset($this->__asset_properties[$pr]))
							{
								// check whether this is new or existibng
								if (isset($this->__asset_properties[$pr]['temp']))
								{
									$this->__asset_properties[$pr]['temp']->meta = $pv;
								}
								else
								{
									$this->ownDatastore[$this->__asset_properties[$pr]]->meta = $pv;
								}
							}
						}
					}

				default:
					// if the property exists, populate the props
					if ($this->propExists($k))
					{
						$this->prop($k, $v);
					}
			}
		}

		return $this;
	}

	public function statusOptions(){
		return ($this->__status_equals) ? $this->__asset_properties[$this->__status_equals]['temp']->options : false;
	}

	public function getStatusValue(){
		$options = $this->statusOptions();
		return ($options && Helpers::isAssocArray($options) && isset($options[$this->status])) ? $options[$this->status] : $this->status;
	}

	public function status()
	{
		$num_args = func_num_args();

		 switch ($num_args)
		 {
			case 0:
				// return all props as beans
				return $this->status;
				break;

			default:
				$val = func_get_arg(0);
				$this->status = $val;
		 }
	}

	public function statusActive(){
		if($this->__status_equals){

			if(!isset($this->__asset->properties[$this->__status_equals]['published'])){
				throw new DatastoreException("Statused assests need a published option in the asset definition");
			}

			$activeOptions = $this->__asset->properties[$this->__status_equals]['published'];

			return is_array($activeOptions) ? in_array($this->status, $activeOptions) : $activeOptions === $this->status;

		}
		return true;
	}



	public static function image_url($image_id)
	{
		return Storage::url('file.jpg');

		// get_instance()->load->model('database/files_model');

		// $image = get_instance()->files_model->get_where(array('id' => $image_id), NULL, 1);

		// return ($image) ? $image->url() : 'assets/modules/blog/images/feature-placeholder.png';
	}

	public static function getAssetByTypeValue($type,$value)
	{
		$asset = DatastoreModel::where('type' , $type)->where('value', $value)->where('namespace', 'asset')->orderBy('modified', 'desc')->first();
		if($asset){
			return self::getAsset($asset->type, $asset->id);
		}
		return false;
	}

	public function urlPath(){
		return Asset::getPath($this->type);
	}

	public function getViewName($prefix = 'frontend.ams'){
		return $this->__asset::getFilename($prefix);
	}

	public function __get($name){
		return $this->__model->{$name};
	}

}
