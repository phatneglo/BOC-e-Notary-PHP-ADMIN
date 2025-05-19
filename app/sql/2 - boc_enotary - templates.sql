-- Template Updates
-- Template ID 2: Importer Renewal Form
UPDATE document_templates SET html_content = '
  <div class="header">
    <div class="logo">BOC LOGO</div>
    <h1>REPUBLIC OF THE PHILIPPINES</h1>
    <h1>BUREAU OF CUSTOMS</h1>
    <h2>IMPORTER RENEWAL FORM</h2>
  </div>

  <div class="section">
    <div class="section-title">COMPANY INFORMATION</div>
    
    <div class="field-row">
      <div class="field-label">Company Name:</div>
      <div class="field-value">{{company_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Company Address:</div>
      <div class="field-value">{{company_address}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Tax Identification Number:</div>
      <div class="field-value">{{company_tin}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">SEC Registration Number:</div>
      <div class="field-value">{{sec_registration}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Original Accreditation Number:</div>
      <div class="field-value">{{original_accreditation_number}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Accreditation Expiry Date:</div>
      <div class="field-value">{{accreditation_expiry_date}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">RENEWAL INFORMATION</div>
    
    <div class="field-row">
      <div class="field-label">Reason for Renewal:</div>
      <div class="field-value">{{renewal_reason}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Changes Since Last Accreditation:</div>
      <div class="field-value">{{changes_description}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">AUTHORIZED REPRESENTATIVE</div>
    
    <div class="field-row">
      <div class="field-label">Full Name:</div>
      <div class="field-value">{{authorized_rep_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Position/Designation:</div>
      <div class="field-value">{{authorized_rep_position}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Email Address:</div>
      <div class="field-value">{{authorized_rep_email}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Mobile Number:</div>
      <div class="field-value">{{authorized_rep_mobile}}</div>
    </div>
  </div>
  
  <div class="declaration">
    <p>I declare under the penalties of perjury that this renewal application has been made in good faith, verified by me, and to the best of my knowledge and belief, is true and correct. I further affirm that I will continue to comply with all Bureau of Customs rules and regulations pertaining to importer accreditation.</p>
    
    <div class="field-row">
      <div class="field-label">Date:</div>
      <div class="field-value">{{declaration_date}}</div>
    </div>
  </div>
  
  <div class="signature-section">
    <div style="display: flex; justify-content: space-between;">
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px; display: flex; align-items: flex-end; justify-content: center;">
          <img src="{{applicant_signature}}" alt="Signature" style="max-height: 60px; max-width: 100%;">
        </div>
        <div class="signature-line"></div>
        <div>Signature of Applicant</div>
        <div style="font-weight: bold;">{{company_name}}</div>
      </div>
      
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px;"></div>
        <div class="signature-line"></div>
        <div>Approved by</div>
        <div style="font-weight: bold;">Bureau of Customs</div>
      </div>
    </div>
  </div>
  
  <div class="footer">
    <p>BUREAU OF CUSTOMS E-NOTARY SYSTEM | IMPORTER RENEWAL FORM | PAGE 1 OF 1</p>
    <p>Form No. IR-001 | Rev. 05/2025</p>
  </div>
' WHERE template_id = 2;

-- Template ID 3: Customs Broker Registration
UPDATE document_templates SET html_content = '
  <div class="header">
    <div class="logo">BOC LOGO</div>
    <h1>REPUBLIC OF THE PHILIPPINES</h1>
    <h1>BUREAU OF CUSTOMS</h1>
    <h2>CUSTOMS BROKER REGISTRATION</h2>
  </div>

  <div class="section">
    <div class="section-title">PERSONAL INFORMATION</div>
    
    <div class="field-row">
      <div class="field-label">Full Name:</div>
      <div class="field-value">{{broker_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Home Address:</div>
      <div class="field-value">{{broker_address}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Email Address:</div>
      <div class="field-value">{{broker_email}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Mobile Number:</div>
      <div class="field-value">{{broker_mobile}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Tax Identification Number:</div>
      <div class="field-value">{{broker_tin}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">PROFESSIONAL INFORMATION</div>
    
    <div class="field-row">
      <div class="field-label">PRC License Number:</div>
      <div class="field-value">{{prc_license_number}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Date of Licensure:</div>
      <div class="field-value">{{license_date}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">License Expiry Date:</div>
      <div class="field-value">{{license_expiry}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Professional Organization:</div>
      <div class="field-value">{{professional_org}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">BUSINESS INFORMATION</div>
    
    <div class="field-row">
      <div class="field-label">Business Name:</div>
      <div class="field-value">{{business_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Business Address:</div>
      <div class="field-value">{{business_address}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Business Type:</div>
      <div class="field-value">{{business_type}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">DTI/SEC Registration Number:</div>
      <div class="field-value">{{business_registration}}</div>
    </div>
  </div>
  
  <div class="declaration">
    <p>I declare under the penalties of perjury that this registration has been made in good faith, verified by me, and to the best of my knowledge and belief, is true and correct. I further affirm that I will comply with all Bureau of Customs rules and regulations pertaining to customs broker registration.</p>
    
    <div class="field-row">
      <div class="field-label">Date:</div>
      <div class="field-value">{{declaration_date}}</div>
    </div>
  </div>
  
  <div class="signature-section">
    <div style="display: flex; justify-content: space-between;">
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px; display: flex; align-items: flex-end; justify-content: center;">
          <img src="{{broker_signature}}" alt="Signature" style="max-height: 60px; max-width: 100%;">
        </div>
        <div class="signature-line"></div>
        <div>Signature of Broker</div>
        <div style="font-weight: bold;">{{broker_name}}</div>
      </div>
      
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px;"></div>
        <div class="signature-line"></div>
        <div>Approved by</div>
        <div style="font-weight: bold;">Bureau of Customs</div>
      </div>
    </div>
  </div>
  
  <div class="footer">
    <p>BUREAU OF CUSTOMS E-NOTARY SYSTEM | CUSTOMS BROKER REGISTRATION | PAGE 1 OF 1</p>
    <p>Form No. CBR-001 | Rev. 05/2025</p>
  </div>
' WHERE template_id = 3;

-- Template ID 4: Affidavit of No Change
UPDATE document_templates SET html_content = '
  <div class="header">
    <div class="logo">BOC LOGO</div>
    <h1>REPUBLIC OF THE PHILIPPINES</h1>
    <h1>BUREAU OF CUSTOMS</h1>
    <h2>AFFIDAVIT OF NO CHANGE</h2>
  </div>

  <div class="section">
    <div class="section-title">AFFIANT INFORMATION</div>
    
    <div class="field-row">
      <div class="field-label">Name of Affiant:</div>
      <div class="field-value">{{affiant_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Position/Designation:</div>
      <div class="field-value">{{affiant_position}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Company Name:</div>
      <div class="field-value">{{company_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Company Address:</div>
      <div class="field-value">{{company_address}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Tax Identification Number:</div>
      <div class="field-value">{{company_tin}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">DECLARATION</div>
    
    <div class="field-row">
      <div class="field-label">Original Registration/Filing Date:</div>
      <div class="field-value">{{original_filing_date}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Document/Registration Number:</div>
      <div class="field-value">{{registration_number}}</div>
    </div>
    
    <p class="affidavit-text">
      I, {{affiant_name}}, of legal age, Filipino, and with office address at {{company_address}}, after having been sworn in accordance with law, hereby depose and state:
    </p>
    
    <p class="affidavit-text">
      1. That I am the {{affiant_position}} of {{company_name}}, a corporation duly organized and existing under Philippine laws;
    </p>
    
    <p class="affidavit-text">
      2. That {{company_name}} is currently registered with the Bureau of Customs under Registration No. {{registration_number}} dated {{original_filing_date}};
    </p>
    
    <p class="affidavit-text">
      3. That I hereby certify that there have been NO CHANGES in the company information, corporate structure, ownership, authorized representatives, and other material information since the last filing with the Bureau of Customs;
    </p>
    
    <p class="affidavit-text">
      4. That I am executing this Affidavit to attest to the truth of the foregoing facts and for whatever legal purpose it may serve.
    </p>
  </div>
  
  <div class="signature-section">
    <div style="display: flex; justify-content: space-between;">
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px; display: flex; align-items: flex-end; justify-content: center;">
          <img src="{{affiant_signature}}" alt="Signature" style="max-height: 60px; max-width: 100%;">
        </div>
        <div class="signature-line"></div>
        <div>Signature of Affiant</div>
        <div style="font-weight: bold;">{{affiant_name}}</div>
      </div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">JURAT</div>
    
    <p class="jurat-text">
      SUBSCRIBED AND SWORN to before me this {{notary_day}} day of {{notary_month}}, {{notary_year}} in the City/Municipality of {{notary_city}}, Philippines. Affiant exhibited to me his/her valid identification as follows:
    </p>
    
    <div class="field-row">
      <div class="field-label">ID Type and Number:</div>
      <div class="field-value">{{affiant_id_type}} - {{affiant_id_number}}</div>
    </div>
  </div>
  
  <div class="footer">
    <p>BUREAU OF CUSTOMS E-NOTARY SYSTEM | AFFIDAVIT OF NO CHANGE | PAGE 1 OF 1</p>
    <p>Form No. ANC-001 | Rev. 05/2025</p>
  </div>
' WHERE template_id = 4;

-- Template ID 6: Deed of Donation
UPDATE document_templates SET html_content = '
  <div class="header">
    <div class="logo">BOC LOGO</div>
    <h1>REPUBLIC OF THE PHILIPPINES</h1>
    <h1>BUREAU OF CUSTOMS</h1>
    <h2>DEED OF DONATION</h2>
  </div>

  <div class="section">
    <div class="section-title">DONOR INFORMATION</div>
    
    <div class="field-row">
      <div class="field-label">Name of Donor:</div>
      <div class="field-value">{{donor_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Address of Donor:</div>
      <div class="field-value">{{donor_address}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Citizenship/Country:</div>
      <div class="field-value">{{donor_citizenship}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Tax Identification Number:</div>
      <div class="field-value">{{donor_tin}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">DONEE INFORMATION</div>
    
    <div class="field-row">
      <div class="field-label">Name of Donee/Organization:</div>
      <div class="field-value">{{donee_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Address of Donee/Organization:</div>
      <div class="field-value">{{donee_address}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Type of Organization:</div>
      <div class="field-value">{{donee_type}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">SEC/Registration Number:</div>
      <div class="field-value">{{donee_registration}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Tax Identification Number:</div>
      <div class="field-value">{{donee_tin}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">DONATION DETAILS</div>
    
    <div class="field-row">
      <div class="field-label">Description of Donated Items:</div>
      <div class="field-value">{{donation_description}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Bill of Lading/Airway Bill Number:</div>
      <div class="field-value">{{bill_number}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Customs Entry Number:</div>
      <div class="field-value">{{customs_entry_number}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Estimated Value:</div>
      <div class="field-value">{{donation_value}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Purpose of Donation:</div>
      <div class="field-value">{{donation_purpose}}</div>
    </div>
  </div>
  
  <div class="declaration">
    <p>
      NOW, THEREFORE, in consideration of the foregoing premises, the DONOR hereby voluntarily and irrevocably gives, transfers, and conveys by way of donation, unto the said DONEE, its successors and assigns, the above-described property.
    </p>
    
    <p>
      IN WITNESS WHEREOF, the parties have hereunto set their hands this {{execution_day}} day of {{execution_month}}, {{execution_year}} at {{execution_place}}, Philippines.
    </p>
  </div>
  
  <div class="signature-section">
    <div style="display: flex; justify-content: space-between;">
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px; display: flex; align-items: flex-end; justify-content: center;">
          <img src="{{donor_signature}}" alt="Signature" style="max-height: 60px; max-width: 100%;">
        </div>
        <div class="signature-line"></div>
        <div>Signature of Donor</div>
        <div style="font-weight: bold;">{{donor_name}}</div>
      </div>
      
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px; display: flex; align-items: flex-end; justify-content: center;">
          <img src="{{donee_signature}}" alt="Signature" style="max-height: 60px; max-width: 100%;">
        </div>
        <div class="signature-line"></div>
        <div>Signature of Donee</div>
        <div style="font-weight: bold;">{{donee_name}}</div>
      </div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">WITNESSES</div>
    
    <div style="display: flex; justify-content: space-between;">
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px;"></div>
        <div class="signature-line"></div>
        <div>Witness</div>
        <div style="font-weight: bold;">{{witness1_name}}</div>
      </div>
      
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px;"></div>
        <div class="signature-line"></div>
        <div>Witness</div>
        <div style="font-weight: bold;">{{witness2_name}}</div>
      </div>
    </div>
  </div>
  
  <div class="footer">
    <p>BUREAU OF CUSTOMS E-NOTARY SYSTEM | DEED OF DONATION | PAGE 1 OF 1</p>
    <p>Form No. DOD-001 | Rev. 05/2025</p>
  </div>
' WHERE template_id = 6;

-- Template ID 7: Re-exportation Commitment
UPDATE document_templates SET html_content = '
  <div class="header">
    <div class="logo">BOC LOGO</div>
    <h1>REPUBLIC OF THE PHILIPPINES</h1>
    <h1>BUREAU OF CUSTOMS</h1>
    <h2>RE-EXPORTATION COMMITMENT</h2>
  </div>

  <div class="section">
    <div class="section-title">COMPANY INFORMATION</div>
    
    <div class="field-row">
      <div class="field-label">Company Name:</div>
      <div class="field-value">{{company_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Company Address:</div>
      <div class="field-value">{{company_address}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Tax Identification Number:</div>
      <div class="field-value">{{company_tin}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">SEC/DTI Registration Number:</div>
      <div class="field-value">{{company_registration}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">AUTHORIZED REPRESENTATIVE</div>
    
    <div class="field-row">
      <div class="field-label">Name:</div>
      <div class="field-value">{{representative_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Position/Designation:</div>
      <div class="field-value">{{representative_position}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Contact Number:</div>
      <div class="field-value">{{representative_contact}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">IMPORTATION DETAILS</div>
    
    <div class="field-row">
      <div class="field-label">Import Entry Number:</div>
      <div class="field-value">{{import_entry_number}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Date of Entry:</div>
      <div class="field-value">{{entry_date}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Description of Goods:</div>
      <div class="field-value">{{goods_description}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Quantity/Unit:</div>
      <div class="field-value">{{goods_quantity}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Value:</div>
      <div class="field-value">{{goods_value}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Reason for Importation:</div>
      <div class="field-value">{{importation_reason}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">RE-EXPORTATION COMMITMENT</div>
    
    <div class="field-row">
      <div class="field-label">Committed Re-exportation Date:</div>
      <div class="field-value">{{reexport_date}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Destination Country:</div>
      <div class="field-value">{{destination_country}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Intended Port of Exit:</div>
      <div class="field-value">{{exit_port}}</div>
    </div>
  </div>
  
  <div class="declaration">
    <p>
      I, {{representative_name}}, in my capacity as {{representative_position}} of {{company_name}}, do hereby commit to re-export the above-described goods on or before the committed re-exportation date. I understand that failure to comply with this commitment may result in the imposition of appropriate duties, taxes, penalties, and other sanctions as provided by law.
    </p>
    
    <p>
      I further declare under the penalties of perjury that this commitment has been made in good faith, verified by me, and to the best of my knowledge and belief, is true and correct.
    </p>
    
    <div class="field-row">
      <div class="field-label">Date:</div>
      <div class="field-value">{{commitment_date}}</div>
    </div>
  </div>
  
  <div class="signature-section">
    <div style="display: flex; justify-content: space-between;">
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px; display: flex; align-items: flex-end; justify-content: center;">
          <img src="{{representative_signature}}" alt="Signature" style="max-height: 60px; max-width: 100%;">
        </div>
        <div class="signature-line"></div>
        <div>Signature of Representative</div>
        <div style="font-weight: bold;">{{representative_name}}</div>
      </div>
      
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px;"></div>
        <div class="signature-line"></div>
        <div>Acknowledged by</div>
        <div style="font-weight: bold;">Bureau of Customs</div>
      </div>
    </div>
  </div>
  
  <div class="footer">
    <p>BUREAU OF CUSTOMS E-NOTARY SYSTEM | RE-EXPORTATION COMMITMENT | PAGE 1 OF 1</p>
    <p>Form No. REC-001 | Rev. 05/2025</p>
  </div>
' WHERE template_id = 7;

-- Template ID 8: Authorization Letter
UPDATE document_templates SET html_content = '
  <div class="header">
    <div class="logo">BOC LOGO</div>
    <h1>REPUBLIC OF THE PHILIPPINES</h1>
    <h1>BUREAU OF CUSTOMS</h1>
    <h2>AUTHORIZATION LETTER</h2>
  </div>

  <div class="section">
    <div class="section-title">DATE AND PLACE</div>
    
    <div class="field-row">
      <div class="field-label">Date:</div>
      <div class="field-value">{{authorization_date}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Place:</div>
      <div class="field-value">{{authorization_place}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">ADDRESSEE</div>
    
    <div class="field-row">
      <div class="field-label">To:</div>
      <div class="field-value">{{addressee_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Position/Office:</div>
      <div class="field-value">{{addressee_position}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Office Address:</div>
      <div class="field-value">{{addressee_address}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">AUTHORIZING PARTY</div>
    
    <div class="field-row">
      <div class="field-label">Name:</div>
      <div class="field-value">{{authorizer_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Position:</div>
      <div class="field-value">{{authorizer_position}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Company:</div>
      <div class="field-value">{{company_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Address:</div>
      <div class="field-value">{{company_address}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Contact Number:</div>
      <div class="field-value">{{authorizer_contact}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">AUTHORIZED REPRESENTATIVE</div>
    
    <div class="field-row">
      <div class="field-label">Name:</div>
      <div class="field-value">{{representative_name}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Position:</div>
      <div class="field-value">{{representative_position}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">ID Type:</div>
      <div class="field-value">{{representative_id_type}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">ID Number:</div>
      <div class="field-value">{{representative_id_number}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Contact Number:</div>
      <div class="field-value">{{representative_contact}}</div>
    </div>
  </div>
  
  <div class="section">
    <div class="section-title">AUTHORIZATION DETAILS</div>
    
    <div class="field-row">
      <div class="field-label">Transaction/Purpose:</div>
      <div class="field-value">{{authorization_purpose}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Reference Numbers:</div>
      <div class="field-value">{{reference_numbers}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Validity Period:</div>
      <div class="field-value">From {{validity_start}} to {{validity_end}}</div>
    </div>
    
    <div class="field-row">
      <div class="field-label">Special Instructions:</div>
      <div class="field-value">{{special_instructions}}</div>
    </div>
  </div>
  
  <div class="declaration">
    <p>
      I, {{authorizer_name}}, in my capacity as {{authorizer_position}} of {{company_name}}, do hereby authorize {{representative_name}} to represent me/our company in the above-mentioned transaction/purpose. All acts performed by the authorized representative within the scope of this authorization shall be deemed as acts done by me/our company.
    </p>
    
    <p>
      This authorization shall be valid for the period indicated above unless sooner revoked in writing.
    </p>
  </div>
  
  <div class="signature-section">
    <div style="display: flex; justify-content: space-between;">
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px; display: flex; align-items: flex-end; justify-content: center;">
          <img src="{{authorizer_signature}}" alt="Signature" style="max-height: 60px; max-width: 100%;">
        </div>
        <div class="signature-line"></div>
        <div>Signature of Authorizing Party</div>
        <div style="font-weight: bold;">{{authorizer_name}}</div>
      </div>
      
      <div style="text-align: center; width: 250px;">
        <div style="height: 60px; display: flex; align-items: flex-end; justify-content: center;">
          <img src="{{representative_signature}}" alt="Signature" style="max-height: 60px; max-width: 100%;">
        </div>
        <div class="signature-line"></div>
        <div>Signature of Authorized Representative</div>
        <div style="font-weight: bold;">{{representative_name}}</div>
      </div>
    </div>
  </div>
  
  <div class="footer">
    <p>BUREAU OF CUSTOMS E-NOTARY SYSTEM | AUTHORIZATION LETTER | PAGE 1 OF 1</p>
    <p>Form No. AL-001 | Rev. 05/2025</p>
  </div>
' WHERE template_id = 8;

-- Template Fields for Template ID 2: Importer Renewal Form
INSERT INTO template_fields (template_id, field_name, field_label, field_type, is_required, placeholder, field_order, validation_rules, help_text, field_width, is_visible, section_name, created_at)
VALUES
(2, 'company_name', 'Company Name', 'text', true, 'Enter registered company name', 1, '', 'Legal name as registered with SEC/DTI', 'full', true, 'Company Information', NOW()),
(2, 'company_address', 'Company Address', 'textarea', true, 'Enter company address', 2, '', 'Principal place of business', 'full', true, 'Company Information', NOW()),
(2, 'company_tin', 'Tax Identification Number', 'text', true, 'Enter TIN', 3, '', 'Format: XXX-XXX-XXX-XXX', 'full', true, 'Company Information', NOW()),
(2, 'sec_registration', 'SEC Registration Number', 'text', true, 'Enter SEC registration number', 4, '', 'For corporations and partnerships', 'full', true, 'Company Information', NOW()),
(2, 'original_accreditation_number', 'Original Accreditation Number', 'text', true, 'Enter original accreditation number', 5, '', 'Accreditation number from initial registration', 'full', true, 'Company Information', NOW()),
(2, 'accreditation_expiry_date', 'Accreditation Expiry Date', 'date', true, '', 6, '', 'Expiration date of current accreditation', 'full', true, 'Company Information', NOW()),
(2, 'renewal_reason', 'Reason for Renewal', 'select', true, 'Select reason for renewal', 7, '', 'Primary reason for requesting renewal', 'full', true, 'Renewal Information', NOW()),
(2, 'changes_description', 'Changes Since Last Accreditation', 'textarea', false, 'Describe any changes since last accreditation', 8, '', 'Describe changes to company structure, operations, etc.', 'full', true, 'Renewal Information', NOW()),
(2, 'authorized_rep_name', 'Authorized Representative Name', 'text', true, 'Enter full name', 9, '', 'Person authorized to represent the company', 'full', true, 'Representative Information', NOW()),
(2, 'authorized_rep_position', 'Position/Designation', 'text', true, 'Enter position', 10, '', 'Official position in the company', 'full', true, 'Representative Information', NOW()),
(2, 'authorized_rep_email', 'Email Address', 'email', true, 'Enter email address', 11, '', 'Official email for communications', 'full', true, 'Representative Information', NOW()),
(2, 'authorized_rep_mobile', 'Mobile Number', 'text', true, 'Enter mobile number', 12, '', 'Format: 09XXXXXXXXX', 'full', true, 'Representative Information', NOW()),
(2, 'declaration_date', 'Declaration Date', 'date', true, '', 13, '', 'Date when this form is signed', 'full', true, 'Declaration', NOW()),
(2, 'applicant_signature', 'Applicant Signature', 'signature', true, '', 14, '', 'Digital signature of applicant', 'full', true, 'Declaration', NOW());

-- Template Fields for Template ID 3: Customs Broker Registration
INSERT INTO template_fields (template_id, field_name, field_label, field_type, is_required, placeholder, field_order, validation_rules, help_text, field_width, is_visible, section_name, created_at)
VALUES
(3, 'broker_name', 'Full Name', 'text', true, 'Enter your full name', 1, '', 'Legal name as it appears on your ID', 'full', true, 'Personal Information', NOW()),
(3, 'broker_address', 'Home Address', 'textarea', true, 'Enter your home address', 2, '', 'Current residential address', 'full', true, 'Personal Information', NOW()),
(3, 'broker_email', 'Email Address', 'email', true, 'Enter your email address', 3, '', 'Active email for communications', 'full', true, 'Personal Information', NOW()),
(3, 'broker_mobile', 'Mobile Number', 'text', true, 'Enter your mobile number', 4, '', 'Format: 09XXXXXXXXX', 'full', true, 'Personal Information', NOW()),
(3, 'broker_tin', 'Tax Identification Number', 'text', true, 'Enter your TIN', 5, '', 'Format: XXX-XXX-XXX-XXX', 'full', true, 'Personal Information', NOW()),
(3, 'prc_license_number', 'PRC License Number', 'text', true, 'Enter your PRC license number', 6, '', 'Customs Broker license number', 'full', true, 'Professional Information', NOW()),
(3, 'license_date', 'Date of Licensure', 'date', true, '', 7, '', 'Date when license was issued', 'full', true, 'Professional Information', NOW()),
(3, 'license_expiry', 'License Expiry Date', 'date', true, '', 8, '', 'Date when license expires', 'full', true, 'Professional Information', NOW()),
(3, 'professional_org', 'Professional Organization', 'text', false, 'Enter professional organization', 9, '', 'Organization membership (if applicable)', 'full', true, 'Professional Information', NOW()),
(3, 'business_name', 'Business Name', 'text', false, 'Enter business name', 10, '', 'Registered business name (if applicable)', 'full', true, 'Business Information', NOW()),
(3, 'business_address', 'Business Address', 'textarea', false, 'Enter business address', 11, '', 'Office or business location', 'full', true, 'Business Information', NOW()),
(3, 'business_type', 'Business Type', 'select', false, 'Select business type', 12, '', 'Select the appropriate business structure', 'full', true, 'Business Information', NOW()),
(3, 'business_registration', 'DTI/SEC Registration Number', 'text', false, 'Enter registration number', 13, '', 'Business registration number (if applicable)', 'full', true, 'Business Information', NOW()),
(3, 'declaration_date', 'Declaration Date', 'date', true, '', 14, '', 'Date when this form is signed', 'full', true, 'Declaration', NOW()),
(3, 'broker_signature', 'Broker Signature', 'signature', true, '', 15, '', 'Digital signature of broker', 'full', true, 'Declaration', NOW());

-- Template Fields for Template ID 4: Affidavit of No Change
INSERT INTO template_fields (template_id, field_name, field_label, field_type, is_required, placeholder, field_order, validation_rules, help_text, field_width, is_visible, section_name, created_at)
VALUES
(4, 'affiant_name', 'Name of Affiant', 'text', true, 'Enter full name of affiant', 1, '', 'Full legal name of person making the affidavit', 'full', true, 'Affiant Information', NOW()),
(4, 'affiant_position', 'Position/Designation', 'text', true, 'Enter position', 2, '', 'Position or title in the company', 'full', true, 'Affiant Information', NOW()),
(4, 'company_name', 'Company Name', 'text', true, 'Enter company name', 3, '', 'Registered company name', 'full', true, 'Affiant Information', NOW()),
(4, 'company_address', 'Company Address', 'textarea', true, 'Enter company address', 4, '', 'Current company address', 'full', true, 'Affiant Information', NOW()),
(4, 'company_tin', 'Tax Identification Number', 'text', true, 'Enter TIN', 5, '', 'Company TIN (Format: XXX-XXX-XXX-XXX)', 'full', true, 'Affiant Information', NOW()),
(4, 'original_filing_date', 'Original Registration/Filing Date', 'date', true, '', 6, '', 'Date of initial registration', 'full', true, 'Declaration', NOW()),
(4, 'registration_number', 'Document/Registration Number', 'text', true, 'Enter registration number', 7, '', 'Original registration or document number', 'full', true, 'Declaration', NOW()),
(4, 'affiant_signature', 'Affiant Signature', 'signature', true, '', 8, '', 'Digital signature of affiant', 'full', true, 'Signature Section', NOW()),
(4, 'notary_day', 'Day', 'text', true, 'day', 9, '', 'Day of notarization (in ordinal form: 1st, 2nd, etc.)', 'half', true, 'Jurat', NOW()),
(4, 'notary_month', 'Month', 'text', true, 'month', 10, '', 'Month of notarization', 'half', true, 'Jurat', NOW()),
(4, 'notary_year', 'Year', 'text', true, 'year', 11, '', 'Year of notarization', 'half', true, 'Jurat', NOW()),
(4, 'notary_city', 'City/Municipality', 'text', true, 'Enter city/municipality', 12, '', 'Location of notarization', 'half', true, 'Jurat', NOW()),
(4, 'affiant_id_type', 'ID Type', 'text', true, 'Enter ID type', 13, '', 'Type of government ID presented', 'half', true, 'Jurat', NOW()),
(4, 'affiant_id_number', 'ID Number', 'text', true, 'Enter ID number', 14, '', 'ID number of presented identification', 'half', true, 'Jurat', NOW());

-- Template Fields for Template ID 6: Deed of Donation
INSERT INTO template_fields (template_id, field_name, field_label, field_type, is_required, placeholder, field_order, validation_rules, help_text, field_width, is_visible, section_name, created_at)
VALUES
(6, 'donor_name', 'Name of Donor', 'text', true, 'Enter full name of donor', 1, '', 'Full legal name of donating party', 'full', true, 'Donor Information', NOW()),
(6, 'donor_address', 'Address of Donor', 'textarea', true, 'Enter address of donor', 2, '', 'Complete address of donor', 'full', true, 'Donor Information', NOW()),
(6, 'donor_citizenship', 'Citizenship/Country', 'text', true, 'Enter citizenship/country', 3, '', 'Citizenship or country of origin', 'full', true, 'Donor Information', NOW()),
(6, 'donor_tin', 'Tax Identification Number', 'text', false, 'Enter TIN if applicable', 4, '', 'TIN of donor (if applicable)', 'full', true, 'Donor Information', NOW()),
(6, 'donee_name', 'Name of Donee/Organization', 'text', true, 'Enter name of donee/organization', 5, '', 'Name of receiving organization', 'full', true, 'Donee Information', NOW()),
(6, 'donee_address', 'Address of Donee/Organization', 'textarea', true, 'Enter address of donee', 6, '', 'Complete address of donee', 'full', true, 'Donee Information', NOW()),
(6, 'donee_type', 'Type of Organization', 'select', true, 'Select organization type', 7, '', 'Type of receiving organization', 'full', true, 'Donee Information', NOW()),
(6, 'donee_registration', 'SEC/Registration Number', 'text', true, 'Enter registration number', 8, '', 'Organization registration number', 'full', true, 'Donee Information', NOW()),
(6, 'donee_tin', 'Tax Identification Number', 'text', true, 'Enter TIN', 9, '', 'Organization TIN', 'full', true, 'Donee Information', NOW()),
(6, 'donation_description', 'Description of Donated Items', 'textarea', true, 'Describe donated items in detail', 10, '', 'Detailed description of all donated items', 'full', true, 'Donation Details', NOW()),
(6, 'bill_number', 'Bill of Lading/Airway Bill Number', 'text', true, 'Enter bill number', 11, '', 'Shipping document reference number', 'full', true, 'Donation Details', NOW()),
(6, 'customs_entry_number', 'Customs Entry Number', 'text', true, 'Enter customs entry number', 12, '', 'Import entry declaration number', 'full', true, 'Donation Details', NOW()),
(6, 'donation_value', 'Estimated Value', 'text', true, 'Enter value in PHP', 13, '', 'Estimated value of donated items', 'full', true, 'Donation Details', NOW()),
(6, 'donation_purpose', 'Purpose of Donation', 'textarea', true, 'Enter purpose of donation', 14, '', 'Explain how donated items will be used', 'full', true, 'Donation Details', NOW()),
(6, 'execution_day', 'Day', 'text', true, 'day', 15, '', 'Day of execution (numeric)', 'third', true, 'Declaration', NOW()),
(6, 'execution_month', 'Month', 'text', true, 'month', 16, '', 'Month of execution', 'third', true, 'Declaration', NOW()),
(6, 'execution_year', 'Year', 'text', true, 'year', 17, '', 'Year of execution', 'third', true, 'Declaration', NOW()),
(6, 'execution_place', 'Place of Execution', 'text', true, 'Enter place', 18, '', 'City/Municipality where document is signed', 'full', true, 'Declaration', NOW()),
(6, 'donor_signature', 'Donor Signature', 'signature', true, '', 19, '', 'Digital signature of donor', 'full', true, 'Signature Section', NOW()),
(6, 'donee_signature', 'Donee Signature', 'signature', true, '', 20, '', 'Digital signature of donee representative', 'full', true, 'Signature Section', NOW()),
(6, 'witness1_name', 'Witness 1 Name', 'text', true, 'Enter witness name', 21, '', 'Full name of first witness', 'half', true, 'Witnesses', NOW()),
(6, 'witness2_name', 'Witness 2 Name', 'text', true, 'Enter witness name', 22, '', 'Full name of second witness', 'half', true, 'Witnesses', NOW());

-- Template Fields for Template ID 7: Re-exportation Commitment
INSERT INTO template_fields (template_id, field_name, field_label, field_type, is_required, placeholder, field_order, validation_rules, help_text, field_width, is_visible, section_name, created_at)
VALUES
(7, 'company_name', 'Company Name', 'text', true, 'Enter company name', 1, '', 'Registered name of company', 'full', true, 'Company Information', NOW()),
(7, 'company_address', 'Company Address', 'textarea', true, 'Enter company address', 2, '', 'Complete business address', 'full', true, 'Company Information', NOW()),
(7, 'company_tin', 'Tax Identification Number', 'text', true, 'Enter TIN', 3, '', 'Company TIN (Format: XXX-XXX-XXX-XXX)', 'full', true, 'Company Information', NOW()),
(7, 'company_registration', 'SEC/DTI Registration Number', 'text', true, 'Enter registration number', 4, '', 'Business registration number', 'full', true, 'Company Information', NOW()),
(7, 'representative_name', 'Name', 'text', true, 'Enter representative name', 5, '', 'Full name of authorized representative', 'full', true, 'Authorized Representative', NOW()),
(7, 'representative_position', 'Position/Designation', 'text', true, 'Enter position', 6, '', 'Official position in company', 'full', true, 'Authorized Representative', NOW()),
(7, 'representative_contact', 'Contact Number', 'text', true, 'Enter contact number', 7, '', 'Mobile or landline number', 'full', true, 'Authorized Representative', NOW()),
(7, 'import_entry_number', 'Import Entry Number', 'text', true, 'Enter import entry number', 8, '', 'Customs import entry declaration number', 'full', true, 'Importation Details', NOW()),
(7, 'entry_date', 'Date of Entry', 'date', true, '', 9, '', 'Date of importation', 'full', true, 'Importation Details', NOW()),
(7, 'goods_description', 'Description of Goods', 'textarea', true, 'Describe imported goods', 10, '', 'Detailed description of imported items', 'full', true, 'Importation Details', NOW()),
(7, 'goods_quantity', 'Quantity/Unit', 'text', true, 'Enter quantity and unit', 11, '', 'Quantity and unit of measurement', 'full', true, 'Importation Details', NOW()),
(7, 'goods_value', 'Value', 'text', true, 'Enter value in PHP', 12, '', 'Declared value of imported goods', 'full', true, 'Importation Details', NOW()),
(7, 'importation_reason', 'Reason for Importation', 'textarea', true, 'Explain reason for importation', 13, '', 'Purpose for temporary importation', 'full', true, 'Importation Details', NOW()),
(7, 'reexport_date', 'Committed Re-exportation Date', 'date', true, '', 14, '', 'Date by which goods will be re-exported', 'full', true, 'Re-exportation Commitment', NOW()),
(7, 'destination_country', 'Destination Country', 'text', true, 'Enter destination country', 15, '', 'Country to which goods will be re-exported', 'full', true, 'Re-exportation Commitment', NOW()),
(7, 'exit_port', 'Intended Port of Exit', 'text', true, 'Enter port of exit', 16, '', 'Port through which goods will exit Philippines', 'full', true, 'Re-exportation Commitment', NOW()),
(7, 'commitment_date', 'Date', 'date', true, '', 17, '', 'Date of signing commitment', 'full', true, 'Declaration', NOW()),
(7, 'representative_signature', 'Representative Signature', 'signature', true, '', 18, '', 'Digital signature of representative', 'full', true, 'Signature Section', NOW());

-- Template Fields for Template ID 8: Authorization Letter
INSERT INTO template_fields (template_id, field_name, field_label, field_type, is_required, placeholder, field_order, validation_rules, help_text, field_width, is_visible, section_name, created_at)
VALUES
(8, 'authorization_date', 'Date', 'date', true, '', 1, '', 'Date of authorization', 'full', true, 'Date and Place', NOW()),
(8, 'authorization_place', 'Place', 'text', true, 'Enter place', 2, '', 'City/Municipality where letter is signed', 'full', true, 'Date and Place', NOW()),
(8, 'addressee_name', 'To', 'text', true, 'Enter addressee name/office', 3, '', 'Name or office to whom letter is addressed', 'full', true, 'Addressee', NOW()),
(8, 'addressee_position', 'Position/Office', 'text', false, 'Enter position/office', 4, '', 'Position or office of addressee', 'full', true, 'Addressee', NOW()),
(8, 'addressee_address', 'Office Address', 'textarea', false, 'Enter office address', 5, '', 'Address of addressee', 'full', true, 'Addressee', NOW()),
(8, 'authorizer_name', 'Name', 'text', true, 'Enter authorizer name', 6, '', 'Full name of person granting authorization', 'full', true, 'Authorizing Party', NOW()),
(8, 'authorizer_position', 'Position', 'text', true, 'Enter position', 7, '', 'Position in company', 'full', true, 'Authorizing Party', NOW()),
(8, 'company_name', 'Company', 'text', true, 'Enter company name', 8, '', 'Registered company name', 'full', true, 'Authorizing Party', NOW()),
(8, 'company_address', 'Address', 'textarea', true, 'Enter company address', 9, '', 'Company address', 'full', true, 'Authorizing Party', NOW()),
(8, 'authorizer_contact', 'Contact Number', 'text', true, 'Enter contact number', 10, '', 'Contact number of authorizer', 'full', true, 'Authorizing Party', NOW()),
(8, 'representative_name', 'Name', 'text', true, 'Enter representative name', 11, '', 'Full name of authorized representative', 'full', true, 'Authorized Representative', NOW()),
(8, 'representative_position', 'Position', 'text', false, 'Enter position', 12, '', 'Position of representative (if applicable)', 'full', true, 'Authorized Representative', NOW()),
(8, 'representative_id_type', 'ID Type', 'text', true, 'Enter ID type', 13, '', 'Type of ID presented by representative', 'half', true, 'Authorized Representative', NOW()),
(8, 'representative_id_number', 'ID Number', 'text', true, 'Enter ID number', 14, '', 'ID number of representative', 'half', true, 'Authorized Representative', NOW()),
(8, 'representative_contact', 'Contact Number', 'text', true, 'Enter contact number', 15, '', 'Contact number of representative', 'full', true, 'Authorized Representative', NOW()),
(8, 'authorization_purpose', 'Transaction/Purpose', 'textarea', true, 'Enter transaction/purpose', 16, '', 'Detailed purpose of authorization', 'full', true, 'Authorization Details', NOW()),
(8, 'reference_numbers', 'Reference Numbers', 'text', false, 'Enter reference numbers', 17, '', 'Related document or transaction numbers', 'full', true, 'Authorization Details', NOW()),
(8, 'validity_start', 'Validity Start Date', 'date', true, '', 18, '', 'When authorization begins', 'half', true, 'Authorization Details', NOW()),
(8, 'validity_end', 'Validity End Date', 'date', true, '', 19, '', 'When authorization expires', 'half', true, 'Authorization Details', NOW()),
(8, 'special_instructions', 'Special Instructions', 'textarea', false, 'Enter special instructions', 20, '', 'Additional instructions or limitations', 'full', true, 'Authorization Details', NOW()),
(8, 'authorizer_signature', 'Authorizer Signature', 'signature', true, '', 21, '', 'Digital signature of authorizer', 'full', true, 'Signature Section', NOW()),
(8, 'representative_signature', 'Representative Signature', 'signature', true, '', 22, '', 'Digital signature of representative', 'full', true, 'Signature Section', NOW());