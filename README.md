# 飞书SDK

[![Build Status](https://travis-ci.org/limingxinleo/feishu-sdk.svg?branch=master)](https://travis-ci.org/limingxinleo/feishu-sdk)

```
composer require hyperfx/feishu
```


```
use Hyperf\Di\Annotation\Inject;
use HyperfX\Feishu\Application;

class FeishuController 
{
  /**
   * @Inject
   * @var Application
   */
  protected $feishuApplication;
  
  public function getUser($code)
  {
      $user = $this->feishuApplication->users->default->getUserInfo($code);
  }
  
  public funtion getDepartmentList()
  {
      $departments = $this->feishuApplication->departments->default->getDepartments();
  }
}
```
