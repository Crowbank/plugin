<?php 
class Calendar {
    
    /**
     * Constructor
     */
    public function __construct($year, $month, $class_func){
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
        $this->currentYear = $year;
        $this->currentMonth = $month;
        $this->class_func = $class_func;
    }
    
    /********************* PROPERTY ********************/
    private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
    
    private $currentYear;
    
    private $currentMonth;
    
    private $currentDay=0;
    
    private $currentDate=null;
    
    private $daysInMonth=0;
    
    private $naviHref= null;
    
    private $class_map;
    
    /********************* PUBLIC **********************/
    
    /**
     * print out the calendar
     */
    public function show() {        
        $this->daysInMonth=$this->_daysInMonth($month,$year);
        
        $content='<div id="calendar">'.
            '<div class="box">'.
            $this->_createNavi().
            '</div>'.
            '<div class="box-content">'.
            '<ul class="label">'.$this->_createLabels().'</ul>';
            $content.='<div class="clear"></div>';
            $content.='<ul class="dates">';
            
            $weeksInMonth = $this->_weeksInMonth($month,$year);
            // Create weeks in a month
            for( $i=0; $i<$weeksInMonth; $i++ ){
                
                //Create days in a week
                for($j=1;$j<=7;$j++){
                    $content.=$this->_showDay($i*7+$j);
                }
            }
            
            $content.='</ul>';
            
            $content.='<div class="clear"></div>';
            
            $content.='</div>';
            
            $content.='</div>';
            return $content;
    }
    
    /********************* PRIVATE **********************/
    /**
     * create the li element for ul
     */
    private function _showDay($cellNumber){
        
        if($this->currentDay==0){
            
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
            
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                
                $this->currentDay=1;
                
            }
        }
        
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
            $date = strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay));
            
            $this->currentDate = date('Y-m-d',$date);
            
            $cellContent = $this->currentDay;
            
            $this->currentDay++;
            
        }else{
            
            $this->currentDate =null;
            
            $cellContent=null;
        }
        
        $h = '<li id="li-'.$this->currentDate.'" class=" ';
        if ($this->currentDate)
            $h .= $this->class_func($date);
        $h .= ($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' '));
        
        $h .= ($cellContent==null?'mask':'').'">'.$cellContent.'</li>';
        
        return $h;
    }
    
    /**
     * create navigation
     */
    private function _createNavi(){
        
        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
        
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
        
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
        
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;
        
        return
        '<div class="header">'.
        '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Prev</a>'.
        '<span class="title">'.date('Y M',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
        '<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
        '</div>';
    }
    
    /**
     * create calendar week labels
     */
    private function _createLabels(){
        
        $content='';
        
        foreach($this->dayLabels as $index=>$label){
            
            $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
            
        }
        
        return $content;
    }
    
    
    
    /**
     * calculate number of weeks in a particular month
     */
    private function _weeksInMonth($month=null,$year=null){
        
        if( null==($year) ) {
            $year =  date("Y",time());
        }
        
        if(null==($month)) {
            $month = date("m",time());
        }
        
        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);
        
        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);
        
        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));
        
        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));
        
        if($monthEndingDay<$monthStartDay){
            
            $numOfweeks++;
            
        }
        
        return $numOfweeks;
    }
    
    /**
     * calculate number of days in a particular month
     */
    private function _daysInMonth($month=null,$year=null){
        
        if(null==($year))
            $year =  date("Y",time());
            
            if(null==($month))
                $month = date("m",time());
                
                return date('t',strtotime($year.'-'.$month.'-01'));
    }
    
}