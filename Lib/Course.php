<?php
/**
 * Created by mohuishou<1@lailin.xyz>.
 * User: mohuishou<1@lailin.xyz>
 * Date: 2016/8/6 0006
 * Time: 17:12
 */
namespace Mohuishou\Lib;
use QL\QueryList;

require_once 'function.php';

class Course extends JwcBase{

    protected function spider($params=[],$page=0,$pageSize=50)
    {
        //构造待抓取的URL
        $params_all = [
            'kch' => '', //课程号
            'kcm' => '', //课程名
            'jsm' => '', //教师
            'xsjc' => '', //开课系所（学院）全称：电子信息学院
            'skxq' => '', //上课星期 1-7
            'skjc' => '', //上课节次 1-12
            'xaqh' => '', //校区 01:望江 02:华西 03:江安
            'jxlh' => '', //教学楼
            'jash' => '', //教室
            'pageSize' => $pageSize,
            'pageNumber'=>$page,
            'actionType' => 1,
        ];
        $params=array_merge($params_all,$params);
        //要显示的列
        $showColumn = "&showColumn=kkxsjc%23%BF%AA%BF%CE%CF%B5&showColumn=kch%23%BF%CE%B3%CC%BA%C5&showColumn=kcm%23%BF%CE%B3%CC%C3%FB&showColumn=kxh%23%BF%CE%D0%F2%BA%C5&showColumn=xf%23%D1%A7%B7%D6&showColumn=kslxmc%23%BF%BC%CA%D4%C0%E0%D0%CD&showColumn=skjs%23%BD%CC%CA%A6&showColumn=zcsm%23%D6%DC%B4%CE&showColumn=skxq%23%D0%C7%C6%DA&showColumn=skjc%23%BD%DA%B4%CE&showColumn=xqm%23%D0%A3%C7%F8&showColumn=jxlm%23%BD%CC%D1%A7%C2%A5&showColumn=jasm%23%BD%CC%CA%D2&showColumn=bkskrl%23%BF%CE%C8%DD%C1%BF&showColumn=xss%23%D1%A7%C9%FA%CA%FD&showColumn=xkxzsm%23%D1%A1%BF%CE%CF%DE%D6%C6%CB%B5%C3%F7";

        //对参数转换字符编码
        foreach ($params as &$v){
            $v=iconv('UTF-8','GBK//IGNORE',$v);
        }
        $params = http_build_query($params) . $showColumn;
        $url = 'http://202.115.47.141/courseSearchAction.do?' . $params;

        //构造抓取规则
        $rule = [
            'college' => ['td:eq(0)', 'text'],//开课学院
            'courseId' => ['td:eq(1)', 'text'],//课程号
            'name' => ['td:eq(2)', 'text'],//课程名
            'lessonId' => ['td:eq(3)', 'text'],//课序号
            'credit' => ['td:eq(4)', 'text'],//学分
            'examType' => ['td:eq(5)', 'text'],//考试类型
            'teacher' => ['td:eq(6)', 'text','','cbTeacher'],//教师
            'allWeek' => ['td:eq(7)', 'text', '', "cbWeek"],//周次
            'week' => ['td:eq(8)', 'text'],//星期
            'session' => ['td:eq(9)', 'text', '',"cbWeek"],//节次
            'campus' => ['td:eq(10)', 'text'],//校区
            'building' => ['td:eq(11)', 'text'],//教学楼
            'classroom' => ['td:eq(12)', 'text'],//教室
            'max' => ['td:eq(13)', 'text'],//课容量
            'studentNumber' => ['td:eq(14)', 'text'],//学生数
            'courseLimit' => ['td:eq(15)', 'text'],//选课限制
            "callback" => "removeSpace"
        ];

        $html = $this->get($url);
        $data = QueryList::Query($html, $rule, '.odd', '','',true)->data;
        return $data;
    }

    /**
     * @author mohuishou<1@lailin.xyz>
     * @param array $params
     * @param int $page
     * @param int $pageSize
     * @return mixed
     */
    public function getCourse($params=[],$page=0,$pageSize=50){
        return $this->login()->spider($params,$page,$pageSize);
    }

    /**
     * 根据教师名字获取
     * @author mohuishou<1@lailin.xyz>
     * @param $teacher
     * @param int $page
     * @param int $pageSize
     * @return mixed
     */
    public function getCourseByTeacher($teacher,$page=0,$pageSize=50){
        $params['jsm']=$teacher;
        return $this->login()->spider($params,$page,$pageSize);
    }

    /**
     * 根据课程名获取
     * @author mohuishou<1@lailin.xyz>
     * @param $name
     * @param int $page
     * @param int $pageSize
     * @return mixed
     */
    public function getCourseByName($name,$page=0,$pageSize=50){
        $params['kcm']=$name;
        return $this->login()->spider($params,$page,$pageSize);
    }

}