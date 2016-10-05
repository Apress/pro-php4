<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output encoding="utf-8" method="html" indent="yes" />

  <xsl:template match="/Recordset">
    <html>
      <head>
        <title>XSL Travel</title>
      </head>
      <body>
        <h1>Travel Packages</h1>
        <table border="0">
          <xsl:for-each select="Travelpackage">
            <tr>
              <td>
                <xsl:text>Country_name</xsl:text>
              </td>
              <td>
                <xsl:value-of select="Country_name" />
              </td>
            </tr>
            <tr>
              <td>
                <xsl:text>City</xsl:text>
              </td>
              <td>
                <xsl:value-of select="City" />
              </td>
            </tr>
            <tr>
              <td>
                <xsl:text>Resort</xsl:text>
              </td>
              <td>
                <xsl:value-of select="Resort" />
              </td>
            </tr>
            <tr>
             <td>
               <xsl:text>Resort_rating</xsl:text>
             </td>
             <td>
               <xsl:value-of select="Resort_rating" />
             </td>
            </tr>
            <tr>
              <td>
                <xsl:text>Resort_typeofholiday</xsl:text>
              </td>
              <td>
                <xsl:value-of select="Resort_watersports" />
              </td>
            </tr>
            <tr>
              <td>
                <xsl:text>Resort_watersports</xsl:text>
              </td>
              <td>
                <xsl:value-of select="Resort_watersports" />
              </td>
            </tr>
            <tr>
              <td>
                <xsl:text>Resort_meals</xsl:text>
              </td>
              <td>
                <xsl:value-of select="Resort_meals" />
              </td>
            </tr>
            <tr>
              <td>
                <xsl:text>Resort_drinks</xsl:text>
              </td>
              <td>
                <xsl:value-of select="Resort_drinks" />
              </td>
            </tr>
            <tr>
              <td>
                <xsl:text>Package_dateofdep</xsl:text>
              </td>
              <td>
                <xsl:value-of select="*/Package_dateofdep" />
              </td>
            </tr>
            <tr>
              <td>
                <xsl:text>Package_price</xsl:text>
              </td>
              <td>
                <xsl:value-of select="*/Package_price" />
              </td>
            </tr>
            <tr>
              <td colspan="2"><hr /></td>
            </tr>
          </xsl:for-each>
        </table>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
