<?php

class Stock
{
    protected $API = 'http://stock.natapp1.cc/Api/';
    /**
     * 查询账户信息
     * @param string $IP 券商交易服务器IP
     * @param integer $Version 设置客户端的版本号
     * @param integer $YybID 营业部代码
     * @param string $AccountNo 完整的登录账号
     * @param string $TradeAccount 交易账号，一般与登录帐号相同.
     * @param string $JyPassword 交易密码
     * @param string $Port 券商交易服务器端口
     * @param string $TxPassword 通讯密码
     * @param integer $Category 表示查询信息的种类，0资金 1股份  2当日委托 3当日成交 4可撤单 5股东代码
     */
    //获取账户的数据

    function QueryData($IP, $Version, $YybID, $AccountNo, $TradeAccount, $JyPassword, $Port, $TxPassword, $Category)
    {

        $api = $this->API;//接口地址

        $data = 'IP=' . $IP . '&Version=' . $Version . '&YybID=' . $YybID . '&AccountNo=' . $AccountNo . '&TradeAccount=' . $TradeAccount . '&JyPassword=' . $JyPassword . '&Port=' . $Port . '&TxPassword=' . $TxPassword . '&Category=' . $Category;

        $header = ['Content-Type' => 'application/x-www-form-urlencoded'];//默认方式

        $url = $api . 'QueryData';

        $res = $this->https_request($url, $header, $data);//CURL获取数据

        return $res;

    }

    /**
     * 查询账户信息（多个种类）
     * @param string $IP 券商交易服务器IP
     * @param integer $Version 设置客户端的版本号
     * @param integer $YybID 营业部代码
     * @param string $AccountNo 完整的登录账号
     * @param string $TradeAccount 交易账号，一般与登录帐号相同.
     * @param string $JyPassword 交易密码
     * @param string $Port 券商交易服务器端口
     * @param string $TxPassword 通讯密码
     * @param integer $Category 表示查询信息的种类，0资金 1股份  2当日委托 3当日成交 4可撤单 5股东代码 如0,1
     * @param string $Count 查询的个数
     */

    //获取账户的多项信息数据
    function QueryDatas($IP, $Version, $YybID, $AccountNo, $TradeAccount, $JyPassword, $Port, $TxPassword, $Category, $Count)
    {

        $api = $this->API;//接口地址

        $data = 'IP=' . $IP . '&Version=' . $Version . '&YybID=' . $YybID . '&AccountNo=' . $AccountNo . '&TradeAccount=' . $TradeAccount . '&JyPassword=' . $JyPassword . '&Port=' . $Port . '&TxPassword=' . $TxPassword . '&Category=' . $Category . '&Count=' . $Count;

        $url = $api . 'QueryDatas';

        $header = ['Content-Type' => 'application/x-www-form-urlencoded'];//默认方式

        $res = $this->https_request($url, $header, $data);//CURL获取数据

        return $res;
    }


    /**
     * 下委托交易证券（买入或卖出）（单个）
     * @param string $IP 券商交易服务器IP
     * @param integer $Version 设置客户端的版本号
     * @param integer $YybID 营业部代码
     * @param string $AccountNo 完整的登录账号
     * @param string $TradeAccount 交易账号，一般与登录帐号相同.
     * @param string $JyPassword 交易密码
     * @param string $Port 券商交易服务器端口
     * @param string $TxPassword 通讯密码
     * @param integer $Category 表示委托的种类，0买入 1卖出
     * @param integer $PriceType 表示报价方式，0上海限价委托 深圳限价委托 1(市价委托)深圳对方最优价格 2(市价委托)深圳本方最优价格 3(市价委托)   深圳即时成交剩余撤销 4(市价委托)上海五档即成剩撤 深圳五档即成剩撤 5(市价委托)深圳全额成交或撤销 6(市价委托)上海五档即成转限价
     * @param string $Gddm 股东代码 交易上海股票填上海的股东代码；交易深圳的股票填入深圳的股东代码
     * @param string $Zqdm 证券代码
     * @param string $Price 委托价格
     * @param string $Quantity 委托数量
     */
    function SendOrder($IP, $Version, $YybID, $AccountNo, $TradeAccount, $JyPassword, $Port, $TxPassword, $Category, $PriceType, $Gddm, $Zqdm, $Price, $Quantity)
    {

        $api = $this->API;//接口地址

        $data = 'IP=' . $IP . '&Version=' . $Version . '&YybID=' . $YybID . '&AccountNo=' . $AccountNo . '&TradeAccount=' . $TradeAccount . '&JyPassword=' . $JyPassword . '&Port=' . $Port . '&TxPassword=' . $TxPassword . '&Category=' . $Category . '&PriceType=' . $PriceType . '&Gddm=' . $Gddm . '&Zqdm=' . $Zqdm . '&Price=' . $Price . '&Quantity=' . $Quantity;

        $url = $api . 'SendOrder';

        $header = ['Content-Type' => 'application/x-www-form-urlencoded'];//默认方式

        $res = $this->https_request($url, $header, $data);//CURL获取数据

        return $res;

    }

    /**
     * 下委托交易证券（买入或卖出）（单个）
     * @param string $IP 券商交易服务器IP
     * @param integer $Version 设置客户端的版本号
     * @param integer $YybID 营业部代码
     * @param string $AccountNo 完整的登录账号
     * @param string $TradeAccount 交易账号，一般与登录帐号相同.
     * @param string $JyPassword 交易密码
     * @param string $Port 券商交易服务器端口
     * @param string $TxPassword 通讯密码
     * @param integer $ExchangeID 交易所ID， 上海1，深圳0(招商证券普通账户深圳是2)
     * @param integer $hth 表示要撤的目标委托的编号
     */
    function CancelOrder($IP, $Version, $YybID, $AccountNo, $TradeAccount, $JyPassword, $Port, $TxPassword, $ExchangeID, $hth)
    {

        $api = $this->API;//接口地址

        $data = 'IP=' . $IP . '&Version=' . $Version . '&YybID=' . $YybID . '&AccountNo=' . $AccountNo . '&TradeAccount=' . $TradeAccount . '&JyPassword=' . $JyPassword . '&Port=' . $Port . '&TxPassword=' . $TxPassword . '&ExchangeID=' . $ExchangeID . '&hth=' . $hth;

        $url = $api . 'CancelOrder';

        $header = ['Content-Type' => 'application/x-www-form-urlencoded'];//默认方式

        $res = $this->https_request($url, $header, $data);//CURL获取数据

        return $res;

    }



    /**
     * 获取账户的历史数据
     * @param string $IP 券商交易服务器IP
     * @param integer $Version 设置客户端的版本号
     * @param integer $YybID 营业部代码
     * @param string $AccountNo 完整的登录账号
     * @param string $TradeAccount 交易账号，一般与登录帐号相同.
     * @param string $JyPassword 交易密码
     * @param string $Port 券商交易服务器端口
     * @param string $TxPassword 通讯密码
     * @param integer $Category 表示查询信息的种类,如0
     * @param integer $StartDate 表示查询信息的开始时间,如20190309
     * @param integer $EndDate 表示查询信息的结束时间,如20190319
     */

    //获取账户的数据
    function QueryHistoryData($IP, $Version, $YybID, $AccountNo, $TradeAccount, $JyPassword, $Port, $TxPassword, $Category)
    {

        $api = $this->API;//接口地址

        $data = 'IP=' . $IP . '&Version=' . $Version . '&YybID=' . $YybID . '&AccountNo=' . $AccountNo . '&TradeAccount=' . $TradeAccount . '&JyPassword=' . $JyPassword . '&Port=' . $Port . '&TxPassword=' . $TxPassword . '&Category=' . $Category . '&StartDate=' . $StartDate . '&EndDate=' . $EndDate;

        $header = ['Content-Type' => 'application/x-www-form-urlencoded'];//默认方式

        $url = $api . 'QueryData';

        $res = $this->https_request($url, $header, $data);//CURL获取数据

        return $res;

    }


    //CURL
    function https_request($url, $header = NULL, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

}
