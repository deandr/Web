<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" >

<xsl:include href="file:///home/scielo/web/htdocs/xsl/sci_common.xsl"/>

<xsl:output method="html" indent="no" />

<xsl:template match="STATISTICS">
<html>

<head>
<link rel="STYLESHEET" type="text/css" href="/css/scielo.css" />
<title>
	<xsl:call-template name="PrintPageTitle" />
</title>

<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="Mon, 06 Jan 1990 00:00:01 GMT" />
</head>

<body bgcolor="#FFFFFF">

<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td>
		<p align="center">
			<a>
				<xsl:attribute name="href">http://<xsl:value-of select="CONTROLINFO/SCIELO_INFO/SERVER"/><xsl:value-of 
					select="CONTROLINFO/SCIELO_INFO/PATH_DATA"/>scielo.php/lng_<xsl:value-of 
					select="CONTROLINFO/LANGUAGE" /></xsl:attribute>
					 
					<img> 
						<xsl:attribute name="src"><xsl:value-of 
							select="CONTROLINFO/SCIELO_INFO/PATH_GENIMG" /><xsl:value-of 
							select="CONTROLINFO/LANGUAGE" />/fbpelogp.gif</xsl:attribute>							
						<xsl:attribute name="border">0</xsl:attribute>
						<xsl:attribute name="align">bottom</xsl:attribute>							
					</img>
			</a>
		</p>
	</td>
	<td align="center" width="80%">
		<blockquote>
			<p align="left">
				<font face="Verdana" size="4" color="#000080">
					<xsl:choose>
						<xsl:when test=" CONTROLINFO/LANGUAGE='en' ">Library Collection</xsl:when>
						<xsl:when test=" CONTROLINFO/LANGUAGE='pt' ">Coleção da biblioteca</xsl:when>
						<xsl:when test=" CONTROLINFO/LANGUAGE='es' ">Colección de la biblioteca</xsl:when>
					</xsl:choose>
				</font>
			</p>
		</blockquote>
	</td>
	</tr>
	<tr>
	<td></td>
	<td>
		<br/>
		<blockquote>
			<p>
				<font size="2" color="#800000"><xsl:call-template name="PrintPageTitle" /></font>
			</p>
		</blockquote>
	</td>
	</tr>
	<tr>
	<td></td>
	<td>
		<blockquote>
			<ul>
				<!-- <li>
					<font face="Verdana" size="2">
						<xsl:choose>
							<xsl:when test=" CONTROLINFO/LANGUAGE='en' ">Library site requests</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='es' ">Informes de acceso de la biblioteca</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='pt' ">Relatório de acesso da biblioteca</xsl:when>
						</xsl:choose> 					
					</font>
				</li>
				<ul>
					<li><a href="http:/site_usage/scielo-an1998.htm">1998</a></li>
					<li><a href="http:/site_usage/scielo-an1999.htm">1999</a></li>
					<li><a href="http:/site_usage/scielo-an2000.htm">2000</a></li>
				       <li>
						2001 
						<xsl:choose>
							<xsl:when test=" CONTROLINFO/LANGUAGE='en' ">(loss data between June 07 and June 09)</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='es' ">(Datos perdidos entre el 07 y el 09 de Junio)</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='pt' ">(dados perdidos desde 7 até 9 de Junho)</xsl:when>
						</xsl:choose>						
					</li>
				        <ul>
        					<li>
							<a href="http:/site_usage/scielo-an2001.htm">
								<xsl:choose>
									<xsl:when test=" CONTROLINFO/LANGUAGE='en' ">From january to june 7</xsl:when>
									<xsl:when test=" CONTROLINFO/LANGUAGE='es' ">Desde Enero hasta el 7 de Junio</xsl:when>
									<xsl:when test=" CONTROLINFO/LANGUAGE='pt' ">A partir de Janeiro até 7 de Junho</xsl:when>
								</xsl:choose>						
							</a>
						</li>
				        	<li>
							<a href="http:/site_usage/reports/scielo-an2001.htm">
								<xsl:choose>
									<xsl:when test=" CONTROLINFO/LANGUAGE='en' ">From june 9</xsl:when>
									<xsl:when test=" CONTROLINFO/LANGUAGE='es' ">Desde el 7 de Junio</xsl:when>
									<xsl:when test=" CONTROLINFO/LANGUAGE='pt' ">De 9 de junho em diante</xsl:when>
								</xsl:choose>						
							</a>
						</li>
				      	</ul>
				       <li><a href="http:/site_usage/reports/scielo-an2002.htm">2002</a></li>
				</ul> -->
				<li>
					<a>
						<xsl:call-template name="AddScieloLogLink">
							<xsl:with-param name="script">sci_journalstat</xsl:with-param>
						</xsl:call-template><xsl:choose>
							<xsl:when test=" CONTROLINFO/LANGUAGE='en' ">Journal requests</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='pt' ">Acessos às revistas</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='es' ">Acceso a las revistas</xsl:when>
						</xsl:choose>
					</a>
				</li>
				<li>
					<a>
						<xsl:call-template name="AddScieloLogLink">
							<xsl:with-param name="script">sci_statiss</xsl:with-param>
						</xsl:call-template><xsl:choose>
							<xsl:when test=" CONTROLINFO/LANGUAGE='en' ">Issue requests</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='pt' ">Acessos aos fascículos</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='es' ">Acceso a los ejemplares</xsl:when>
						</xsl:choose>
					</a>
				</li>
				<!--li>
					<a>
						<xsl:call-template name="AddScieloLogLink">
							<xsl:with-param name="script">sci_statart</xsl:with-param>
						</xsl:call-template><xsl:choose>
							<xsl:when test=" CONTROLINFO/LANGUAGE='en' ">Article requests</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='pt' ">Acessos aos artigos</xsl:when>
							<xsl:when test=" CONTROLINFO/LANGUAGE='es' ">Acceso a los artículos</xsl:when>
						</xsl:choose>
					</a>
				</li-->
			</ul>
		</blockquote>
	</td>
	</tr>
</table>

<p>&#160;</p>

<xsl:apply-templates select="COPYRIGHT" />

</body>

</html>
</xsl:template>

<xsl:template name="PrintPageTitle">
	<xsl:choose>
		<xsl:when test=" CONTROLINFO/LANGUAGE='en' ">Library site usage reports</xsl:when>
		<xsl:when test=" CONTROLINFO/LANGUAGE='pt' ">Relatórios de utilização do site</xsl:when>
		<xsl:when test=" CONTROLINFO/LANGUAGE='es' ">Informes de uso del sítio</xsl:when>
	</xsl:choose>
</xsl:template>

<xsl:template match="COPYRIGHT">
	<xsl:call-template name="COPYRIGHTSCIELO"/>
</xsl:template>

</xsl:stylesheet>