<?php
/**
 +---------------------------------------<br/>
 * 服务类:所有Service的管理类<br/>
 +---------------------------------------
 * @category ittrmonitor
 * @package services
 * @author skygreen skygreen2001@gmail.com
 */
class Manager_ReportService extends Manager
{
    private static $reportoneService;

    /**
     * 提供服务: 统一报表
     */
    public static function serviceReportone()
    {
        if (self::$reportoneService == null) {
            self::$reportoneService = new ServiceReportone();
        }
        return self::$reportoneService;
    }


    private static $userCountService;

    /**
     * 提供服务: 月增用户数
     */
    public static function serviceUserCount()
    {
        if (self::$userCountService == null) {
            self::$userCountService = new ServiceUserCount();
        }
        return self::$userCountService;
    }

    private static $tripModelCountService;

    /**
     * 提供服务: 月增线路数
     */
    public static function serviceTripModelCount()
    {
        if (self::$tripModelCountService == null) {
            self::$tripModelCountService = new ServiceTripModelCount();
        }
        return self::$tripModelCountService;
    }

    private static $tripCountService;

    /**
     * 提供服务: 月增活动数
     */
    public static function serviceTripCount()
    {
        if (self::$tripCountService == null) {
            self::$tripCountService = new ServiceTripCount();
        }
        return self::$tripCountService;
    }

    private static $eAccountCountService;

    /**
     * 提供服务: 月增活动数
     */
    public static function serviceEAccountCount()
    {
        if (self::$eAccountCountService == null) {
            self::$eAccountCountService = new ServiceEAccountCount();
        }
        return self::$eAccountCountService;
    }

    private static $rechargeCountService;

    /**
     * 提供服务: 月充值数
     */
    public static function serviceRechargeCount()
    {
        if (self::$rechargeCountService == null) {
            self::$rechargeCountService = new ServiceRechargeCount();
        }
        return self::$rechargeCountService;
    }

    private static $eTripCountService;

    /**
     * 提供服务: 企办活动数
     */
    public static function serviceETripCount()
    {
        if (self::$eTripCountService == null) {
            self::$eTripCountService = new ServiceETripCount();
        }
        return self::$eTripCountService;
    }

    private static $mTripCountService;

    /**
     * 提供服务: 月办活动数
     */
    public static function serviceMTripCount()
    {
        if (self::$mTripCountService == null) {
            self::$mTripCountService = new ServiceMTripCount();
        }
        return self::$mTripCountService;
    }

    private static $mTripCountAvgService;

    /**
     * 提供服务: 月办活动平均人数
     */
    public static function serviceMTripCountAvg()
    {
        if (self::$mTripCountAvgService == null) {
            self::$mTripCountAvgService = new ServiceMTripCountAvg();
        }
        return self::$mTripCountAvgService;
    }

    private static $mTeamAvgPeopleService;

    /**
     * 提供服务: 月战队平均人数
     */
    public static function serviceMTeamAvgPeople()
    {
        if (self::$mTeamAvgPeopleService == null) {
            self::$mTeamAvgPeopleService = new ServiceMTeamAvgPeople();
        }
        return self::$mTeamAvgPeopleService;
    }

    private static $eArtistCountService;

    /**
     * 提供服务: 企设计师数
     */
    public static function serviceEArtistCount()
    {
        if (self::$eArtistCountService == null) {
            self::$eArtistCountService = new ServiceEArtistCount();
        }
        return self::$eArtistCountService;
    }

    private static $eTripModelCountService;

    /**
     * 提供服务: 企设线路数
     */
    public static function serviceETripModelCount()
    {
        if (self::$eTripModelCountService == null) {
            self::$eTripModelCountService = new ServiceETripModelCount();
        }
        return self::$eTripModelCountService;
    }

    private static $eMTripModelCountService;

    /**
     * 提供服务: 企月设线路数
     */
    public static function serviceEMTripModelCount()
    {
        if (self::$eMTripModelCountService == null) {
            self::$eMTripModelCountService = new ServiceEMTripModelCount();
        }
        return self::$eMTripModelCountService;
    }

    private static $eHasTripCountService;

    /**
     * 提供服务: 企建活动数
     */
    public static function serviceEHasTripCount()
    {
        if (self::$eHasTripCountService == null) {
            self::$eHasTripCountService = new ServiceEHasTripCount();
        }
        return self::$eHasTripCountService;
    }

    private static $eMTripCountService;

    /**
     * 提供服务: 企月增活动数
     */
    public static function serviceEMTripCount()
    {
        if (self::$eMTripCountService == null) {
            self::$eMTripCountService = new ServiceEMTripCount();
        }
        return self::$eMTripCountService;
    }

    private static $eMBTripCountService;

    /**
     * 提供服务: 企月办活动数
     */
    public static function serviceEMBTripCount()
    {
        if (self::$eMBTripCountService == null) {
            self::$eMBTripCountService = new ServiceEMBTripCount();
        }
        return self::$eMBTripCountService;
    }

    private static $eTripAvgPeopleService;

    /**
     * 提供服务: 企业活动平均人数
     */
    public static function serviceETripAvgPeople()
    {
        if (self::$eTripAvgPeopleService == null) {
            self::$eTripAvgPeopleService = new ServiceETripAvgPeople();
        }
        return self::$eTripAvgPeopleService;
    }

    private static $eTeamAvgPeopleService;

    /**
     * 提供服务: 企业战队平均人数
     */
    public static function serviceETeamAvgPeople()
    {
        if (self::$eTeamAvgPeopleService == null) {
            self::$eTeamAvgPeopleService = new ServiceETeamAvgPeople();
        }
        return self::$eTeamAvgPeopleService;
    }

    private static $eRechargeCountService;

    /**
     * 提供服务: 企充值数
     */
    public static function serviceERechargeCount()
    {
        if (self::$eRechargeCountService == null) {
            self::$eRechargeCountService = new ServiceERechargeCount();
        }
        return self::$eRechargeCountService;
    }

    private static $eConsumptionService;

    /**
     * 提供服务: 企消耗数
     */
    public static function serviceEConsumption()
    {
        if (self::$eConsumptionService == null) {
            self::$eConsumptionService = new ServiceEConsumption();
        }
        return self::$eConsumptionService;
    }

    private static $eLeftCountService;

    /**
     * 提供服务: 企结余数
     */
    public static function serviceELeftCount()
    {
        if (self::$eLeftCountService == null) {
            self::$eLeftCountService = new ServiceELeftCount();
        }
        return self::$eLeftCountService;
    }

    private static $eMRechargeCountService;

    /**
     * 提供服务: 企月充值数
     */
    public static function serviceEMRechargeCount()
    {
        if (self::$eMRechargeCountService == null) {
            self::$eMRechargeCountService = new ServiceEMRechargeCount();
        }
        return self::$eMRechargeCountService;
    }

    private static $eMConsumptionService;

    /**
     * 提供服务: 企月消耗数
     */
    public static function serviceEMConsumption()
    {
        if (self::$eMConsumptionService == null) {
            self::$eMConsumptionService = new ServiceEMConsumption();
        }
        return self::$eMConsumptionService;
    }

    private static $eMLeftCountService;

    /**
     * 提供服务: 企月结余数
     */
    public static function serviceEMLeftCount()
    {
        if (self::$eMLeftCountService == null) {
            self::$eMLeftCountService = new ServiceEMLeftCount();
        }
        return self::$eMLeftCountService;
    }

    private static $eCertifyTimeService;

    /**
     * 提供服务: 企认证时间
     */
    public static function serviceECertifyTime()
    {
        if (self::$eCertifyTimeService == null) {
            self::$eCertifyTimeService = new ServiceECertifyTime();
        }
        return self::$eCertifyTimeService;
    }

    private static $eRechargeTimeService;

    /**
     * 提供服务: 企充值时间表
     */
    public static function serviceERechargeTime()
    {
        if (self::$eRechargeTimeService == null) {
            self::$eRechargeTimeService = new ServiceERechargeTime();
        }
        return self::$eRechargeTimeService;
    }

    private static $eBTripTimeService;

    /**
     * 提供服务: 企办活动时间表
     */
    public static function serviceEBTripTime()
    {
        if (self::$eBTripTimeService == null) {
            self::$eBTripTimeService = new ServiceEBTripTime();
        }
        return self::$eBTripTimeService;
    }

    private static $artistTripModelService;

    /**
     * 提供服务: 设计师线路
     */
    public static function serviceArtistTripModel()
    {
        if (self::$artistTripModelService == null) {
            self::$artistTripModelService = new ServiceArtistTripModel();
        }
        return self::$artistTripModelService;
    }

    private static $artistTMCountService;

    /**
     * 提供服务: 设计师线路数
     */
    public static function serviceArtistTMCount()
    {
        if (self::$artistTMCountService == null) {
            self::$artistTMCountService = new ServiceArtistTMCount();
        }
        return self::$artistTMCountService;
    }

    private static $artistMTMCountService;

    /**
     * 提供服务: 设计师月线路数
     */
    public static function serviceArtistMTMCount()
    {
        if (self::$artistMTMCountService == null) {
            self::$artistMTMCountService = new ServiceArtistMTMCount();
        }
        return self::$artistMTMCountService;
    }

    private static $tMByTCountService;

    /**
     * 提供服务: 线路被活动引用数
     */
    public static function serviceTMByTCount()
    {
        if (self::$tMByTCountService == null) {
            self::$tMByTCountService = new ServiceTMByTCount();
        }
        return self::$tMByTCountService;
    }

    private static $artistTMByTAvgCountService;

    /**
     * 提供服务: 设计师线被活引用平均数
     */
    public static function serviceArtistTMByTAvgCount()
    {
        if (self::$artistTMByTAvgCountService == null) {
            self::$artistTMByTAvgCountService = new ServiceArtistTMByTAvgCount();
        }
        return self::$artistTMByTAvgCountService;
    }

    private static $artistTMByTMaxCountService;

    /**
     * 提供服务: 设计师线被活引用最大数
     */
    public static function serviceArtistTMByTMaxCount()
    {
        if (self::$artistTMByTMaxCountService == null) {
            self::$artistTMByTMaxCountService = new ServiceArtistTMByTMaxCount();
        }
        return self::$artistTMByTMaxCountService;
    }

    private static $artistMTMByTAvgCountService;

    /**
     * 提供服务: 设计师月线被活引用平均数
     */
    public static function serviceArtistMTMByTAvgCount()
    {
        if (self::$artistMTMByTAvgCountService == null) {
            self::$artistMTMByTAvgCountService = new ServiceArtistMTMByTAvgCount();
        }
        return self::$artistMTMByTAvgCountService;
    }

    private static $artistMTMByTMaxCountService;

    /**
     * 提供服务: 设计师月线路被活动引用最大数
     */
    public static function serviceArtistMTMByTMaxCount()
    {
        if (self::$artistMTMByTMaxCountService == null) {
            self::$artistMTMByTMaxCountService = new ServiceArtistMTMByTMaxCount();
        }
        return self::$artistMTMByTMaxCountService;
    }

    private static $tMByTripService;

    /**
     * 提供服务: 被引用活动
     */
    public static function serviceTMByTrip()
    {
        if (self::$tMByTripService == null) {
            self::$tMByTripService = new ServiceTMByTrip();
        }
        return self::$tMByTripService;
    }

    private static $tMByTHoldCountService;

    /**
     * 提供服务: 引用活动举办数
     */
    public static function serviceTMByTHoldCount()
    {
        if (self::$tMByTHoldCountService == null) {
            self::$tMByTHoldCountService = new ServiceTMByTHoldCount();
        }
        return self::$tMByTHoldCountService;
    }

    private static $tripConsumptionService;

    /**
     * 提供服务: 已办活动冻结数
     */
    public static function serviceTripConsumption()
    {
        if (self::$tripConsumptionService == null) {
            self::$tripConsumptionService = new ServiceTripConsumption();
        }
        return self::$tripConsumptionService;
    }

    private static $tripReturnCountService;

    /**
     * 提供服务: 已办活动退还数
     */
    public static function serviceTripReturnCount()
    {
        if (self::$tripReturnCountService == null) {
            self::$tripReturnCountService = new ServiceTripReturnCount();
        }
        return self::$tripReturnCountService;
    }

    private static $tripSettleCountService;

    /**
     * 提供服务: 已办活动结算总数
     */
    public static function serviceTripSettleCount()
    {
        if (self::$tripSettleCountService == null) {
            self::$tripSettleCountService = new ServiceTripSettleCount();
        }
        return self::$tripSettleCountService;
    }

    private static $mTripSettleCountService;

    /**
     * 提供服务: 月办活动结算总数
     */
    public static function serviceMTripSettleCount()
    {
        if (self::$mTripSettleCountService == null) {
            self::$mTripSettleCountService = new ServiceMTripSettleCount();
        }
        return self::$mTripSettleCountService;
    }

}
