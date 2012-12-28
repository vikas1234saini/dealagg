<?php
class Snapdeal extends Parsing{
	public $_code = 'Snapdeal';

	public function getAllowedCategory(){
		return array(Category::BOOKS,Category::MOBILE,Category::MOBILE_ACC);
	}

	public function getWebsiteUrl(){
		return 'http://www.snapdeal.com/';
	}
	public function getSearchURL($query,$category = false){
		if($category == Category::BOOKS){
			return "http://www.snapdeal.com/search?keyword=$query&catId=&categoryId=364&suggested=false&vertical=p&noOfResults=20&clickSrc=go_header&lastKeyword=cream&prodCatId=&changeBackToAll=false&foundInAll=false&categoryIdSearched=&url=&utmContent=&catalogID=&dealDetail=";
		}else if($category == Category::CAMERA){
			return "http://www.snapdeal.com/search?keyword=$query&catId=&categoryId=291&suggested=false&vertical=p&noOfResults=20&clickSrc=go_header&lastKeyword=&prodCatId=&changeBackToAll=false&foundInAll=false&categoryIdSearched=&url=&utmContent=&catalogID=&dealDetail="; // digital camera
			return "http://www.snapdeal.com/search?keyword=$query&catId=&categoryId=292&suggested=false&vertical=p&noOfResults=20&clickSrc=go_header&lastKeyword=&prodCatId=&changeBackToAll=false&foundInAll=false&categoryIdSearched=&url=&utmContent=&catalogID=&dealDetail="; // digital srls
			return "http://www.snapdeal.com/search?keyword=$query&catId=&categoryId=293&suggested=false&vertical=p&noOfResults=20&clickSrc=go_header&lastKeyword=&prodCatId=&changeBackToAll=false&foundInAll=false&categoryIdSearched=&url=&utmContent=&catalogID=&dealDetail="; // camcorder
		}else if($category == Category::CAMERA_ACC){
			return "http://www.snapdeal.com/search?keyword=$query&catId=&categoryId=296&suggested=false&vertical=p&noOfResults=20&clickSrc=go_header&lastKeyword=&prodCatId=&changeBackToAll=false&foundInAll=false&categoryIdSearched=&url=&utmContent=&catalogID=&dealDetail="; //acc
			return "http://www.snapdeal.com/search?keyword=$query&catId=&categoryId=304&suggested=false&vertical=p&noOfResults=20&clickSrc=go_header&lastKeyword=&prodCatId=&changeBackToAll=false&foundInAll=false&categoryIdSearched=&url=&utmContent=&catalogID=&dealDetail="; //memory card
		}else if($category == Category::MOBILE){
			return "http://www.snapdeal.com/search?keyword=$query&catId=0&categoryId=175&suggested=false&vertical=p&noOfResults=20&clickSrc=go_header&lastKeyword=&prodCatId=29&changeBackToAll=false&foundInAll=false&categoryIdSearched=&url=&utmContent=&catalogID=&dealDetail=";
		}else if($category == Category::MOBILE_ACC){
			return "http://www.snapdeal.com/search?keyword=$query&catId=0&categoryId=29&suggested=false&vertical=p&noOfResults=20&clickSrc=go_header&lastKeyword=&prodCatId=175&changeBackToAll=false&foundInAll=false&categoryIdSearched=&url=&utmContent=&catalogID=&dealDetail=";
		}else{
			return "http://www.snapdeal.com/search?keyword=".$query."&catId=&categoryId=0&suggested=false&vertical=&noOfResults=20&clickSrc=go_header&lastKeyword=&prodCatId=&changeBackToAll=false&foundInAll=false&categoryIdSearched=&url=&utmContent=&catalogID=&dealDetail=";
		}
	}
	public function getLogo(){
		return "http://i4.sdlcdn.com/img/snapdeal/sprite/snapdeal_logo_tagline.png";
	}
	public function getData($html,$query,$category){
		$data = array();
		phpQuery::newDocumentHTML($html);
		if(sizeof(pq('#filter-no-results-message')) > 0){
			//return $data;  this div shows always
		}
		if(sizeof(pq('div.product_listing_cont'))){
			foreach(pq('div.product_listing_cont') as $div){
				if(sizeof(pq($div)->find('.product-image'))){
					$image = pq($div)->find('.product-image')->html();
					$url = pq($div)->find('a')->attr('href');
					$name = strip_tags(pq($div)->find('.product_listing_heading')->html());
					$disc_price = pq($div)->find('.product_listing_price_outer')->find('.product_discount_outer ')->html();
					$offer = '';
					$shipping = '' ;
					$stock = 0;
					$cat ='';
					$author = '';
					$data[] = array(
							'name'=>$name,
							'image'=>$image,
							'disc_price'=>$disc_price,
							'url'=>$url,
							'website'=>$this->getCode(),
							'offer'=>$offer,
							'shipping'=>$shipping,
							'stock'=>$stock,
							'author' => $author,
							'cat' => $cat
					);
				}
			}
		}else{
			if(sizeof(pq('.product_grid_cont'))){
				foreach(pq('.product_grid_cont') as $div){
					if(sizeof(pq($div)->find('.product-image'))){
						$image = pq($div)->find('.product-image')->html();
						$url = pq($div)->children('a:first')->attr('href');
						$name = pq($div)->find('.product_grid_cont_heading')->html();
						$org_price = $disc_price = pq($div)->find('.product_grid_cont_price_outer')->children('.product_price')->children('.originalprice ')->html();
						$org_price = $this->clearHtml($org_price);
						$disc_price = pq($div)->find('.product_grid_cont_price_outer')->children('.product_price')->html();;
						$disc_price = $this->clearHtml($disc_price);
						$disc_price = str_replace($org_price, '', $disc_price);
						$offer = '';
						$shipping = '';
						$stock = 0;
						$cat ='';
						$author = '';
						$data[] = array(
								'name'=>$name,
								'image'=>$image,
								'disc_price'=>$disc_price,
								'url'=>$url,
								'website'=>$this->getCode(),
								'offer'=>$offer,
								'shipping'=>$shipping,
								'stock'=>$stock,
								'author' => $author,
								'cat' => $cat
						);
					}
				}
			}else{
				if(sizeof(pq('.container'))){
					foreach(pq('.container') as $div){
						$image = pq($div)->find('.imgCont:first')->html();
						$url = pq($div)->children('a:first')->attr('href');
						$name = pq($div)->find('.product_listing_heading:first')->html();
						pq($div)->find('.product_price')->children('.originalprice')->html('');
						$disc_price = pq($div)->find('.product_price')->html();
						$offer = '';
						$shipping = '';
						$stock = 0;
						$cat ='';
						$author = '';
						$data[] = array(
								'name'=>$name,
								'image'=>$image,
								'disc_price'=>$disc_price,
								'url'=>$url,
								'website'=>$this->getCode(),
								'offer'=>$offer,
								'shipping'=>$shipping,
								'stock'=>$stock,
								'author' => $author,
								'cat' => $cat
						);
					}
				}
			}
		}
		$data2 = array();
		foreach($data as $row){
			$html = $row['image'];
			$html .= '</img>';
			phpQuery::newDocumentHTML($html);
			$img = pq('img')->attr('src');
			if(strpos($img, 'http') === false){
				$img = $this->getWebsiteUrl().$img;
			}
			$row['image'] = $img;
			$data2[] = $row;
		}
		$data2 = $this->cleanData($data2, $query);
		$data2 = $this->bestMatchData($data2, $query,$category);
		return $data2;
	}
}