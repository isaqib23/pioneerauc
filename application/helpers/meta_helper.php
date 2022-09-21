<?php
    function meta($page) {
        $meta = '<meta charset="utf-8">';
        switch ($page) {
            case 'vehicles':
                $meta .= '<title>Your dream car is just a Bid Away!</title>';
                $meta .= '<meta name="description" content="Get the best deals on top brands. Instantly bid online on your favourite brands online or through our live showroom auctions.">';
                break;
            case 'building-and-construction':
                $meta .= '<title>Get the biggest deals on building & construction material.</title>';
                $meta .= '<meta name="description" content="The biggest machines, building materials and equipment are available for bidding. Start building up your bids!">';
                break;
            case 'general-materials':
                $meta .= '<title>Buy or sell machinery, furniture, appliances and more…</title>';
                $meta .= '<meta name="description" content="Get the best deals on machinery, furniture, kitchen equipment, metal items, medical equipment and more. Bid online 24x7 or through our live showroom auctions.">';
                break;
            case 'marine':
                $meta .= '<title>Drop your anchors here for all things marine.</title>';
                $meta .= '<meta name="description" content="Your best chance to sail away is right here. Bid online on yachts, motorboats, sailboats and all things marine.">';
                break;
            case 'contact_us':
                $meta .= '<title>We are here to help – Call us on 800 7466337</title>';
                $meta .= '<meta name="description" content="We are here to answer all your queries. Call us on 800 7466337 for more details.">';
                break;
            case 'about_us':
                $meta .= '<title>We are your favorite auction platform</title>';
                $meta .= '<meta name="description" content="Since its inception in 2008, Pioneer Auctions has been a leader in the industry, living up to its name and introducing many firsts. It was among the first auction houses in the UAE to allow its customers to bid real-time, through live-streaming services. This has evolved into a seamless ‘click of a button’ auction experience that is available 24/7 via our website and our mobile app, and weekly via our auction hall.">';
                break;
            case 'auction_guide':
                $meta .= '<title>The Easiest Way To Bid</title>';
                $meta .= '<meta name="description" content="Bidding at Pioneer Auctions is fast, easy, convenient and rewarding. Know how to bid on your favourite items.">';
                break;
            case 'how_to_register':
                $meta .= '<title>Create your account in 4 easy steps</title>';
                $meta .= '<meta name="description" content="Get your username and password in 4 easy steps. Click on the link to know how.">';
                break;
            default:
                $meta .= '<title>Pioneer Auctions – Bid from anywhere</title>';
                $meta .= '<meta name="description" content="Bid online 24x7 or through our live showroom auctions. Our auction categories are diverse and they include everything from automobiles, to building and construction, to marine equipment, to number plates and more. Bid Now!">';
                break;
        }
        return $meta;
    }
?>