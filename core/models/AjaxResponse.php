<?php
namespace WPMVC\Models;

// Import namespaces
use \WPMVC\Common\Model;

/**
 * The AJAX Response model
 *
 * @package Models
 * @subpackage AjaxResponse
 */
class AjaxResponse extends Model
{
    /**
     * The response status
     *
     * @access protected
     * @var array
     */
    protected $status;

    /**
     * The response data
     *
     * @access protected
     * @var array
     */
    protected $data;

    /**
     * Creates a new AJAX response
     *
     * @access public
     * @param string $status
     * @param array $data
     */
    public function __construct($status = 'error', $data = array())
    {
        $this
            ->setStatus($status)
            ->setData($data);
    }

    /**
     * Sets the response status
     *
     * @access public
     * @param string $status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Sets the response data
     *
     * @access public
     * @param array $data
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Outputs the JSON response status and data
     *
     * @access public
     * @return void
     */
    public function output()
    {
        header('Content-type: application/json');
        $data = array(
            'status' => $this->status,
            'data' => $this->data
        );
        echo json_encode($data);
        die();
    }
}
