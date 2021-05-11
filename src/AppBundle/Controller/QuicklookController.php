<?php

// Moon Ore Update provided by 4tt1c
// https://github.com/timthedevguy/BuySYS/issues/31

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Model\OreReviewModel;
use AppBundle\Entity\SDE\TypeEntity;

class QuicklookController extends Controller {

	private $ore_high = array('1230', '17470', '17471', '1228', '17463', '17464', '1224', '17459', '17460', '20', '17452', '17453', '46689', '46686', '46687', '46683');
    private $moon_ore = array('45492', '45502', '45502', '45502', '45494', '45493', '45495', '45512', '45511', '45498', '45504', '45497', '45499', '45491', '45496', '45500', '45510', '45513', '45490', '45503');
	private $compressed_ore_high = array('28430', '28431', '28432', '46705', '28424', '28425', '28426', '46702', '28427', '28428', '28429', '46703', '28409', '28410', '28411');
	private $ore_other = array('18', '17455', '17456', '1227', '17867', '17868', '46685', '46684');
	private $compressed_ore_other = array('28421', '28422', '28423', '46701', '28415', '28416', '28417', '46700');
	private $ore_low = array('1226', '17448', '17449', '1231', '17444', '17445', '21', '17440', '17441', '46682', '46681', '46680');
	private $compressed_ore_low = array('28406', '28407', '28408', '46698', '28403', '28404', '28405', '46697', '28400', '28401', '28402', '46696');
	private $ore_null = array('22', '17425', '17426', '1223', '17428', '17429', '1225', '17432', '17433',
		'1232', '17436', '17437', '1229', '17865', '17866', '11396', '17869', '17870', '19', '17466', '17467', '46679', '46688', '46678', '46677', '46676', '46675');
	private $compressed_ore_null = array('28412', '28413', '28414', '28397', '28398', '28399', '46695', '28418', '28419', '28420',
		'46704', '28367', '28385', '28387', '46691', '28391', '28392', '28393', '46693', '28388', '28389', '28390', '46692', '28394', '28395', '28396', '46694');
	private $ice = array('16264', '17975', '16265', '17976', '16262', '17978', '16263', '17977', '16267', '16268', '16266', '16269');
	private $gas = array('25268', '28694', '25279', '28695', '25275', '28696', '30375', '30376', '30377', '30370', '30378', '30371', '30372', '30373', '30374', '25273', '28697', '25277', '28698', '25276', '28699', '25278', '28700', '25274', '28701');
	private $minerals = array('34', '35', '36', '37', '38', '39', '40', '11399', '16272', '16274', '17889', '16273', '17888', '17887', '16275');
	private $p0 = array('2268', '2305', '2267', '2288', '2287', '2307', '2272', '2309', '2073', '2310', '2270', '2306', '2286', '2311', '2308');
	private $p1 = array('2393', '2396', '3779', '2401', '2390', '2397', '2392', '3683', '2389', '2399', '2395', '2398', '9828', '2400', '3645');
	private $p2 = array('2329', '3828', '9836', '9832', '44', '3693', '15317', '3725', '3689', '2327', '9842', '2463', '2317', '2321', '3695', '9830', '3697', '9838', '2312', '3691', '2319', '9840', '3775', '2328');
	private $p3 = array('2358', '2345', '2344', '2367', '17392', '2348', '9834', '2366', '2361', '17898', '2360', '2354', '2352', '9846', '9848', '2351', '2349', '2346', '12836', '17136', '28974');
	private $p4 = array('2867', '2868', '2869', '2870', '2871', '2872', '2875', '2876');

	/**
	 * @Route("/quicklook/{ore_type}", name="quicklook")
	 */
	public function indexAction(Request $request, $ore_type)
	{
		$results = Array();
		$subText = "";
		$hideCan = false;
		$eveCentralOK = $this->get("helper")->getSetting("eveCentralOK", "global");

		switch ($ore_type)
		{
			case "high":
				$results = $this->getQuickReview($this->ore_high);
				$subText = "High Sec Ores";
				break;

			case "compressed_high":
				$results = $this->getQuickReview($this->compressed_ore_high);
				$subText = "Compressed High Sec Ores";
				break;

			case "other":
				$results = $this->getQuickReview($this->ore_other);
				$subText = "Other High Sec Ores";
				break;

			case "compressed_other":
				$results = $this->getQuickReview($this->compressed_ore_other);
				$subText = "Other Compressed High Sec Ores";
				break;

			case "low":
				$results = $this->getQuickReview($this->ore_low);
				$subText = "Low Sec Ores";
				break;

			case "compressed_low":
				$results = $this->getQuickReview($this->compressed_ore_low);
				$subText = "Compressed Low Sec Ores";
				break;

			case "null":
				$results = $this->getQuickReview($this->ore_null);
				$subText = "Null Sec Ores";
				break;

			case "compressed_null":
				$results = $this->getQuickReview($this->compressed_ore_null);
				$subText = "Compressed Null Sec Ores";
				break;

            case "moon_ore":
                $results = $this->getQuickReview($this->moon_ore);
                $subText = "Moon Ores";
                break;

			case "all":
				$results = $this->getQuickReview(array_merge($this->ore_high, $this->ore_other, $this->ore_low, $this->ore_null, $this->moon_ore));
				$subText = "All Ores";
				break;

			case "compressed_all":
				$results = $this->getQuickReview(array_merge($this->compressed_ore_high, $this->compressed_ore_other, $this->compressed_ore_low, $this->compressed_ore_null));
				$subText = "All Compressed Ores";
				break;

			case "ice":
				$results = $this->getQuickReview($this->ice);
				$subText = "Ice";
				break;

			case "gas":
				$results = $this->getQuickReview($this->gas);
				$subText = "Gas";
				break;

			case "minerals":
				$results = $this->getQuickReview($this->minerals);
				$subText = "Minerals";
				$hideCan = true;
				break;

			case "p0":
				$results = $this->getQuickReview($this->p0);
				$subText = "P0 Commodities";
				$hideCan = true;
				break;

			case "p1":
				$results = $this->getQuickReview($this->p1);
				$subText = "P1 Commodities";
				$hideCan = true;
				break;

			case "p2":
				$results = $this->getQuickReview($this->p2);
				$subText = "P2 Commodities";
				$hideCan = true;
				break;

			case "p3":
				$results = $this->getQuickReview($this->p3);
				$subText = "P3 Commodities";
				$hideCan = true;
				break;

			case "p4":
				$results = $this->getQuickReview($this->p4);
				$subText = "P4 Commodities";
				$hideCan = true;
				break;

			case "pAll":
				$results = $this->getQuickReview(array_merge($this->p0, $this->p1, $this->p2, $this->p3, $this->p4));
				$subText = "P4 Commodities";
				$hideCan = true;
				break;
		}

		return $this->render('quicklook/index.html.twig', array(
			'title'    => $subText,
			'results'      => $results,
			'hideCan'      => $hideCan
		));
		// Logic
	}

	private function getQuickReview($typeIds)
	{

		$results = array();
		$marketPrices = $this->get("market")->getBuybackPricesForTypes($typeIds);

		foreach ($typeIds as $typeId)
		{

			$eveType = $this->getDoctrine()->getRepository('AppBundle:SDE\TypeEntity')->findOneByTypeID($typeId);
			$oreModel = new OreReviewModel();
			$oreModel->setTypeId($typeId);
			$oreModel->setName($eveType->getTypeName());
			$oreModel->setVolume($eveType->getVolume());
			$oreModel->setIskPer($marketPrices[$typeId]['adjusted']);
			$oreModel->setIskPerM($marketPrices[$typeId]['adjusted'] / $oreModel->getVolume());
			$oreModel->setCanUnits(27500 / $oreModel->getVolume());
			$oreModel->setCanPrice(27500 * $oreModel->getIskPerM());

			$results[] = $oreModel;
		}

		return $this->calculateBlends($results);
	}

	private function calculateBlends($source)
	{

		// Setup impossible Low value and High value
		$high = 0;
		$low = 1000000000;
		$results = array();
		//'11396','17869','17870'
		// Loop through items
		foreach ($source as $item)
		{

			//$item->getTypeId() != '11396' & $item->getTypeId() != '17869' & $item->getTypeId() != '17870' & $item->getTypeId() != '16267' & $item->getTypeId() != '11399' &
			if ($item->getIskPerM() != 0)
			{

				// Get Valuation of 100 units of ore
				$value = floor($item->getIskPerM());

				// See if this is higher than our highest currently
				if ($value > $high)
				{

					$high = $value;
				}

				// See if this is lower than our lowest currently
				if ($value < $low)
				{

					$low = $value;
				}
			}
		}

		// Now we have a min and max we can calculate the spread
		$spread = $high - $low;

		foreach ($source as $item)
		{

			//$item->getTypeId() != '11396' & $item->getTypeId() != '17869' & $item->getTypeId() != '17870' & $item->getTypeId() != '16267' & $item->getTypeId() != '11399' &
			if ($item->getIskPerM() != 0)
			{
				$value = floor($item->getIskPerM());

				// Calculate the percent by getting the spread of the unit
				// ie: it's value minus the min, then divide by the entire spread.
				$percent = ($value - $low) / $spread;

				//$results[$item->name] = $percent;
				$item->setColor($percent);
			} else
			{

				$item->setColor('1');
			}

			if ($item->getIskPerM() == 0)
			{
				$item->setColor('0');
			}
		}

		return $source;
	}
}
