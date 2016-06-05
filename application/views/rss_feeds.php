<?php header ("Content-Type:text/xml"); ?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>';?>
<?php
$rss_feed = '
        <rss version="2.0">
            <channel>
                <title>News </title>
                <link>'.base_url().'</link>
                <description>RSS feed</description>
                <language>en-us</language>
                <copyright>Copyright 2016 News</copyright>';
      $data = '';
    if(!empty($news)) {
        foreach($news as $news_details ) {
            $mysqldate = gmdate(DATE_RFC822, strtotime($news_details->created));
            $data .= ' <item>
                    <title><![CDATA[ '.$news_details->title .' ]]></title>
                    <author><![CDATA['.$news_details->email.']]></author>
                    <link>'. base_url().'news_details/'.$news_details->id.'</link>
                    <description><![CDATA[
                    '.$news_details->description.' ]]>
                    </description>
                    <thumbnail><![CDATA[
                    '.base_url().$upload_dir.$news_details->thumbnail_url.' ]]>
                    </thumbnail>
                    <image><![CDATA[
                    '.base_url().$upload_dir.$news_details->image_url.' ]]>
                    </image>
                    <pubDate><![CDATA['.$mysqldate.']]></pubDate>
                    </item> 
                    ';
        }
    }
$rss_feed .= $data;

$rss_feed .='</channel>
    </rss>';
echo $rss_feed;
