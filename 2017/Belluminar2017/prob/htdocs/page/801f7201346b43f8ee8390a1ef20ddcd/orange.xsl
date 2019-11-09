<?xml version="1.0" encoding="UTF-8"?>
<html xsl:version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
<body style="background-color:#FFB143;font-size:10pt">
<xsl:for-each select="colors/color">
  <div style="color:white;font-size:12pt">
    <xsl:value-of select="description"/>
  </div>
</xsl:for-each>
</body>
</html>