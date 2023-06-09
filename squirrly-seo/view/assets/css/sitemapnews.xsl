<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9" exclude-result-prefixes="s">
    <xsl:template match="/">
        <html lang="en">
            <head>
                <title>XML Sitemap</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <style>body,h1{color:#005a92}body{font-family:Helvetica,Arial,sans-serif;font-size:14px;background:#b0bec7;padding:20px}#content{margin:0 auto;max-width:1200px;background:#fff;padding:20px 30px;-webkit-box-shadow:2px 2px 5px 1px rgba(0,0,0,.2);box-shadow:2px 2px 5px 1px rgba(0,0,0,.2);-webkit-border-radius:5px;border-radius:5px}h1{font-size:20px;line-height:24px;font-weight:700;padding-left:31px;margin:0 0 20px;display:inline-block}h1 a{font:inherit;color:inherit;text-decoration:none}#content table{background-color:#cdcdcd;margin:20px 0 15px;font-size:8pt;width:100%;text-align:left;border:none;border-collapse:collapse;border-bottom:1px solid #005a92}#content table th{white-space:nowrap!important;background:#1c3c50;color:#fff;text-shadow:none;padding:10px}#content table tr:nth-of-type(odd){background-color:#fff}#content table tr:nth-of-type(2n){background-color:#fbfbfb}#content table td{color:#005a92;padding:7px 5px;vertical-align:middle}#content table tr:hover td{background-color:rgb(252 251 255)!important}#content table td a{color:#2680b4!important}#footer{margin:50px 0 10px;text-align:right;font-size:.8em}</style>
            </head>
            <body>
                <div id="content">
                    <h1 style="padding-left:0;">
                        <a href="?sq_feed=sitemap">XML Sitemap  - Go to Index</a>
                    </h1>

                    <table id="sitemap">
                        <thead>
                            <tr bgcolor="#9acd32">
                                <th width="1%"></th>
                                <th style="text-align:left" width="40%">URL</th>
                                <th style="text-align:left" width="40%">Title</th>
                                <th style="text-align:left" width="10%">Publisher</th>
                                <th style="text-align:left" width="10%">Updated at</th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:for-each select="s:urlset/s:url">
                                <tr>
                                    <td>
                                        <xsl:if test="count(image:image/image:loc) > 0">

                                            <xsl:variable name="loc">
                                                <xsl:value-of select="image:image/image:loc"/>
                                            </xsl:variable>
                                            <a href="{$loc}">
                                                <img src="{$loc}" width="50" valign="middle"/>
                                            </a>

                                        </xsl:if>
                                    </td>
                                    <td class="url"><a href="{s:loc}"><xsl:value-of select="s:loc"/></a></td>
                                    <td><xsl:value-of select="news:news/news:title"/></td>
                                    <td><xsl:value-of select="news:news/news:publication/news:name"/></td>
                                    <td><xsl:value-of select="concat(substring(news:news/news:publication_date,0,11),concat(' ', substring(news:news/news:publication_date,12,5)))"/></td>
                                </tr>
                            </xsl:for-each>
                        </tbody>
                    </table>
                    <p id="Footer" class="expl">Generated by
                        <a href="https://wordpress.org/plugins/squirrly-seo/" target="_blank" title="SEO Plugin By Squirrly">Squirrly SEO Plugin</a>
                    </p>
                </div>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>