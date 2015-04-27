<?php

/**
 * Class crud Controller
 */
class CrudController extends AdminController
{
    private $value;
    private $var2;
    private $var3;

    public function __construct()
    {
        if(isset($_GET['var1'])) {
            $this->value = $_GET['var1'];

            if(isset($_GET['var2'])) {
                $this->var2 = $_GET['var2'];

                if (isset($_GET['var3'])) {
                    $this->var3 = $_GET['var3'];
                }
            }
        }


        switch($this->value) {
            case 'cache':
                $endResult = $this->deleteCache('cache/');
                if ($endResult) {
                    echo "Delete was success! All cache was clean.\n";
                } else {
                    echo "Something go wrong, maybe you don`t have access for cache folder\n";
                }
                break;
            case 'addhook':
                $this->createNewHook($this->var2);
                break;
            case 'domain':
                $this->changeDomain($this->var2);
                break;
            case 'linkhook':
                $endResult = $this->linkHook($this->var2, $this->var3);

                if($endResult == 1) {
                    echo "No such module in data base.\n";
                }

                if($endResult == 2) {
                    echo "No such hook in data base.\n";
                }

                if($endResult == 3) {
                    echo "Such hook with module already axist.\n";
                    break;
                }
                if($endResult == 4) {
                    echo "You link was attached to this hook.\n";
                    break;
                }
                break;
            case '?':
                echo "---------------------- Commands ------------------------\n";
                echo "cache                                 - remove cache\n";
                echo "domain [domainname]                   - change site domain\n";
                echo "addhook [hookname]                    - add hook to site\n";
                echo "linkhook [modulename] [hookname]      - add hook to site\n";
                break;
            default:
                echo "You should type some commands, for example \"php console.php ?\" for full information \n";
        }
    }

    /**
     * @param $path string
     *
     * @return bool
     */
    public function deleteCache($path)
    {
        foreach(glob("{$path}/*") as $file)
        {
            if(is_dir($file)) {
                $this->deleteCache($file);
            }
            else {
                unlink($file);
            }
        }
        return true;
    }

    /**
     * @param $name string
     */
    public function createNewHook($name)
    {
        $sql = "SELECT `title` FROM ps_hook WHERE `name` = '$name'";

        $myString1 = Db::getInstance()->executeS($sql);

        if($myString1[0]['title'] == $name) {

            echo "This hook already axist.\n";
            return;
        }
        else {
            $myString =
                Db::getInstance()
                  ->insert('hook', [
                      'name' => $name,
                      'title' => $name,
                      'description' => 'This is a custom hook!',
                  ]);
        }
    }

    public function changeDomain($newDomain)
    {
        $sql = "UPDATE ps_shop SET name = '$newDomain' WHERE id_shop = '1'";
        Db::getInstance()->execute($sql);
        $sql = "UPDATE  ps_shop_url SET domain = '$newDomain' WHERE id_shop = '1'";
        Db::getInstance()->execute($sql);
        $sql = "UPDATE  ps_shop_url SET domain_ssl = '$newDomain' WHERE id_shop = '1'";
        Db::getInstance()->execute($sql);
        $sql = "UPDATE ps_configuration SET value = '$newDomain' WHERE name IN ('PS_SHOP_DOMAIN', 'PS_SHOP_DOMAIN_SSL', 'PS_SHOP_NAME')";
        Db::getInstance()->execute($sql);
    }

    public function linkHook($module, $hookName)
    {
        $sql = "SELECT `id_module` FROM ps_module WHERE `name` = '$module'";
        $mod = Db::getInstance()->executeS($sql);
        if($mod[0]['id_module'] == "")
            return 1;

        $sql = "SELECT `id_hook` FROM ps_hook WHERE `name` = '$hookName'";
        $kooh = Db::getInstance()->executeS($sql);
        if($kooh[0]['id_hook'] == "")
            return 2;

        $myMode = $mod[0]['id_module'];
        $myHook = $kooh[0]['id_hook'];

        $sql = "SELECT * FROM ps_hook_module WHERE `id_module` = '$myMode' AND  `id_hook` = '$myHook'";
        $validation = Db::getInstance()->executeS($sql);

        if($validation[0]['id_module'] != "" && $validation[0]['id_hook'] != "") {
            return 3;
        }

        $sql = "INSERT INTO ps_hook_module VALUES ('$myMode', 1, '$myHook', 1)";
        Db::getInstance()->execute($sql);
        return 4;
    }
}
