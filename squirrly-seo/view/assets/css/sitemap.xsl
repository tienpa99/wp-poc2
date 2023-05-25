<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" xmlns:html="http://www.w3.org/TR/REC-html40" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>XML Sitemap</title>
                <meta content="text/html; charset=utf-8" />
                <style>body,h1{color:#005a92}body{font-family:Helvetica,Arial,sans-serif;font-size:14px;background:#b0bec7;padding:20px}#content{margin:0 auto;max-width:1200px;background:#fff;padding:20px 30px;-webkit-box-shadow:2px 2px 5px 1px rgba(0,0,0,.2);box-shadow:2px 2px 5px 1px rgba(0,0,0,.2);-webkit-border-radius:5px;border-radius:5px}h1{font-size:20px;line-height:24px;font-weight:700;padding-left:31px;margin:0 0 20px;display:inline-block}h1 a{font:inherit;color:inherit;text-decoration:none}#content table{background-color:#cdcdcd;margin:20px 0 15px;font-size:8pt;width:100%;text-align:left;border:none;border-collapse:collapse;border-bottom:1px solid #005a92}#content table th{white-space:nowrap!important;background:#1c3c50;color:#fff;text-shadow:none;padding:10px}#content table tr:nth-of-type(odd){background-color:#fff}#content table tr:nth-of-type(2n){background-color:#fbfbfb}#content table td{color:#005a92;padding:7px 5px;vertical-align:middle}#content table tr:hover td{background-color:rgb(252 251 255)!important}#content table td a{color:#2680b4!important}#footer{margin:50px 0 10px;text-align:right;font-size:.8em}</style>
            </head>
            <body>
                <div id="content">
                    <h1 style="padding-left:0;">
                        <a href="?sq_feed=sitemap">XML Sitemap - Go to Index</a>
                    </h1>

                    <p class="expl">
                        This sitemap contains <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/> URLs.
                    </p>
                    <table id="sitemap">
                        <thead>
                            <tr>
                                <th width="1%"></th>
                                <th width="70%">URL</th>
                                <th width="10%">Priority</th>
                                <th width="10%">Change Freq.</th>
                                <th width="10%">Last Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <xsl:variable name="lower" select="'abcdefghijklmnopqrstuvwxyz'"/>
                            <xsl:variable name="upper" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'"/>

                            <xsl:for-each select="sitemap:urlset/sitemap:url">

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
                                    <td>
                                        <xsl:variable name="itemURL">
                                            <xsl:value-of select="sitemap:loc"/>
                                        </xsl:variable>
                                        <a href="{$itemURL}">
                                            <xsl:value-of select="sitemap:loc"/>
                                        </a>
                                    </td>
                                    <td>
                                        <xsl:if test="sitemap:priority">
                                            <xsl:value-of select="concat(sitemap:priority*100,'%')"/>
                                        </xsl:if>
                                    </td>
                                    <td>
                                        <xsl:if test="sitemap:changefreq">
                                            <xsl:value-of select="concat(translate(substring(sitemap:changefreq, 1, 1),concat($lower, $upper),concat($upper, $lower)),substring(sitemap:changefreq, 2))"/>
                                        </xsl:if>
                                    </td>
                                    <td>
                                        <xsl:if test="sitemap:lastmod">
                                            <xsl:value-of select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/>
                                        </xsl:if>
                                    </td>

                                </tr>
                            </xsl:for-each>
                        </tbody>
                    </table>
                    <p id="footer" class="expl">Generated by
                        <a href="https://wordpress.org/plugins/squirrly-seo/" target="_blank" title="SEO Plugin By Squirrly">Squirrly SEO Plugin</a>
                    </p>
                </div>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>