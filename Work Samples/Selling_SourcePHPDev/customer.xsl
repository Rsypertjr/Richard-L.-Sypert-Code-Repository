<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:foo="http://whatever">
<xsl:output indent="yes"/>

  <!--	selecting period of WEEKLY or MONTHLY -->
	 <xsl:function name = "foo:periodic">
	        <xsl:param name="type"/>
			<xsl:variable name="weekly" select ="'WEEKLY'"/>
			<xsl:variable name="monthly" select ="'MONTHLY'"/>
			<xsl:variable name="w" select ="'W'"/>
			<xsl:variable name="m" select ="'M'"/>
			
		
			<xsl:if test="$type = $weekly">
				<xsl:value-of select="$w"/>
			</xsl:if>
			
			<xsl:if test="$type = $monthly">
				<xsl:value-of select="$m"/>
			</xsl:if>
	 </xsl:function> 

	             <xsl:template match="/">
					<xsl:element name="application">
					   <xsl:apply-templates select="customer/fname"/> 
					   <xsl:apply-templates select="customer/lname"/> 
					   <xsl:apply-templates select="customer/phone"/> 
					   <xsl:apply-templates select="customer/dob_y"/> 
			  		   <xsl:apply-templates select="customer/email"/> 
					   <xsl:apply-templates select="customer/active"/> 
					   <xsl:apply-templates select="customer/type"/> 
					</xsl:element>
				 </xsl:template>  
				
				 <xsl:template match="customer/fname">
				   <xsl:element name="first_name">
					  <xsl:value-of select = "."/>
				   </xsl:element>
				 </xsl:template>
				 
				 <xsl:template match="customer/lname">
				   <xsl:element name="last_name">
					 <xsl:value-of select = "."/>
				   </xsl:element>
				 </xsl:template>
							 
				<xsl:template match="customer/phone">
				   <xsl:element name="phone1">
					 <xsl:value-of select="substring(.,0,4)"/>
				   </xsl:element>
				 
            	   <xsl:element name="phone2">
					 <xsl:value-of select="substring(.,5,3)"/>
				   </xsl:element>
				 
				   <xsl:element name="phone3">
					 <xsl:value-of select="substring(.,9,4)"/>
				   </xsl:element>
			    </xsl:template>  
				 
				 <xsl:template match="customer/dob_y">
				   <xsl:variable name="sl" select='"/"'/>
				   <xsl:element name="dob">
					 <xsl:value-of select="concat(../dob_m,$sl,../dob_d,$sl,../dob_y)"/>
				   </xsl:element>
				 </xsl:template>
      
				 <xsl:template match="customer/email">
				   <xsl:variable name="sl" select='"@"'/>
				   <xsl:element name="email_domain">
					 <xsl:value-of select="substring-after(.,$sl)"/>
				   </xsl:element>
				 </xsl:template>

				 <xsl:template match="customer/active">
				   <xsl:variable name="true" select='"TRUE"'/>
				   <xsl:variable name="false" select='"FALSE"'/>
				   <xsl:variable name="activ" select='"Active"'/>
				   <xsl:variable name="inactive" select='"Inactive"'/>
				   <xsl:element name="email_domain">
					 <xsl:if test=". = $true"> 
							<xsl:value-of select = "$activ"/> 
					 </xsl:if> 
					 <xsl:if test=". = $false"> 
							<xsl:value-of select = "$inactive"/> 
					 </xsl:if> 
				   </xsl:element>
				 </xsl:template>		 
				
			<!--	 <xsl:template match="customer/type">
				   <xsl:variable name="weekly" select ='"WEEKLY"'/>
				   <xsl:variable name="monthly" select ='"MONTHLY"'/>
				   <xsl:variable name="w" select ='"W"'/>
				   <xsl:variable name="m" select ='"M"'/>
			           <xsl:element name="repeat">
					  <xsl:if test=". = $weekly">
						 <xsl:value-of select="$w"/>
					  </xsl:if>
			      	          <xsl:if test=". = $monthly">
						<xsl:value-of select="$m"/>
					  </xsl:if>
			            </xsl:element>	
				 </xsl:template>	-->	 
				 
				 
				  
				 <xsl:template match="customer/type">
				   <xsl:element name="repeat">
					 <xsl:copy-of select="foo:periodic(.)"/>
				   </xsl:element>
				 </xsl:template>   
		  
	    
	

</xsl:stylesheet>
