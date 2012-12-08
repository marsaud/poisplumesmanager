<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Report
 * 
 * @property float $total Description
 * @property float $cb Description
 * @property float $chq Description
 * @property float $chr Description
 * @property float $mon Description
 *
 * @author MAKRIS
 */
class CashFlowReport
{

    /**
     *
     * @var float
     */
    protected $_total = 0;
    /**
     *
     * @var float
     */
    protected $_cb = 0;
    /**
     *
     * @var float
     */
    protected $_chq = 0;
    /**
     *
     * @var float
     */
    protected $_chr = 0;
    /**
     *
     * @var float
     */
    protected $_mon = 0;

    /**
     * 
     * @param string $name
     * 
     * @return mixed
     * 
     * @throws OutOfRangeException
     */
    public function __get($name)
    {
        switch ($name)
        {
            case 'total':
            case 'cb':
            case 'chq':
            case 'chr':
            case 'mon':
                return $this->{'_' . $name};

                break;

            default:
                throw new OutOfRangeException('No ' . $name . ' read property');
                break;
        }
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     * 
     * @return void
     * 
     * @throws OutOfRangeException
     */
    public function __set($name, $value)
    {
        switch ($name)
        {
            case 'total':
            case 'cb':
            case 'chq':
            case 'chr':
            case 'mon':
                $this->{'_' . $name} = (float) $value;
                break;

            default:
                throw new OutOfRangeException('No ' . $name . ' write property');
                break;
        }
    }
    
    public function add(CashFlowReport $report)
    {
        $this->cb += $report->cb;
        $this->chq += $report->chq;
        $this->chr += $report->chr;
        $this->mon += $report->mon;
        $this->total += $report->total;
    }

}
