<?php

namespace Phpsa\Datastore\Models\Traits\Attribute;

use Phpsa\Datastore\Helpers;

/**
 * Trait UserAttribute.
 */
trait DatastoreAttribute
{

	/**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.ams.content.update', ['asset' => $this->content_path, 'id' => $this->id]).'" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.edit').'" class="btn btn-primary"><i class="fas fa-edit"></i></a>';
	}

	  /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {

            return '<a href="'.route('admin.ams.content.destroy', $this).'"
			data-method="delete"
			data-trans-button-cancel="'.__('buttons.general.cancel').'"
			data-trans-button-confirm="'.__('buttons.general.crud.delete').'"
			data-trans-title="'.__('strings.backend.general.are_you_sure').'"
			class="btn btn-danger"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.delete').'"></i></a> ';

    }

	    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {

		if($this->id)
		{
        	return '<div class="btn-group" role="group" aria-label="'.__('labels.backend.access.users.user_actions').'">
			'.$this->edit_button.'
			'.$this->delete_button.'
			</div>';
		}
	}

	public function getContentPathAttribute(){
		return Helpers::getPath($this->type);
	}


	public function getParentsAttribute(){
		$rows = Datastore::find(1);
		$data['parents'] = $this->db->query('select distinct(type), name, accept_limit from datastore where accept LIKE ?', array('%' . $newasset->type . '%'))->result_array();

	}

}