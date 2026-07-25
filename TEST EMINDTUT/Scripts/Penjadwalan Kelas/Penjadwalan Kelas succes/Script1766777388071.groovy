import static com.kms.katalon.core.checkpoint.CheckpointFactory.findCheckpoint
import static com.kms.katalon.core.testcase.TestCaseFactory.findTestCase
import static com.kms.katalon.core.testdata.TestDataFactory.findTestData
import static com.kms.katalon.core.testobject.ObjectRepository.findTestObject
import static com.kms.katalon.core.testobject.ObjectRepository.findWindowsObject
import com.kms.katalon.core.checkpoint.Checkpoint as Checkpoint
import com.kms.katalon.core.cucumber.keyword.CucumberBuiltinKeywords as CucumberKW
import com.kms.katalon.core.mobile.keyword.MobileBuiltInKeywords as Mobile
import com.kms.katalon.core.model.FailureHandling as FailureHandling
import com.kms.katalon.core.testcase.TestCase as TestCase
import com.kms.katalon.core.testdata.TestData as TestData
import com.kms.katalon.core.testng.keyword.TestNGBuiltinKeywords as TestNGKW
import com.kms.katalon.core.testobject.TestObject as TestObject
import com.kms.katalon.core.webservice.keyword.WSBuiltInKeywords as WS
import com.kms.katalon.core.webui.keyword.WebUiBuiltInKeywords as WebUI
import com.kms.katalon.core.windows.keyword.WindowsBuiltinKeywords as Windows
import internal.GlobalVariable as GlobalVariable
import org.openqa.selenium.Keys as Keys

WebUI.openBrowser('')

WebUI.navigateToUrl('https://emindtutor.com/login')

WebUI.setText(findTestObject('Object Repository/Penjadwalan Kelas/Page_Login - eMindTutor/input_Email_email'), 'budi@gmail.com')

WebUI.setEncryptedText(findTestObject('Object Repository/Penjadwalan Kelas/Page_Login - eMindTutor/input_Password_password'), 
    'dtF6YETvWVmufkRU47BlyA==')

WebUI.sendKeys(findTestObject('Object Repository/Penjadwalan Kelas/Page_Login - eMindTutor/input_Password_password'), Keys.chord(
        Keys.ENTER))

WebUI.click(findTestObject('Object Repository/Penjadwalan Kelas/Page_Dashboard  EMindTutor/button_Nanti Saja'))

WebUI.click(findTestObject('Object Repository/Penjadwalan Kelas/Page_Dashboard  EMindTutor/button_Student Management'))

WebUI.click(findTestObject('Object Repository/Penjadwalan Kelas/Page_Dashboard  EMindTutor/a_New Student'))

WebUI.click(findTestObject('Object Repository/Penjadwalan Kelas/Page_EMindTutor/a_Approve'))

WebUI.selectOptionByValue(findTestObject('Object Repository/Penjadwalan Kelas/Page_EMindTutor/select_-- Select Teacher --                _bbce6f'), 
    '3', true)

WebUI.setText(findTestObject('Object Repository/Penjadwalan Kelas/Page_EMindTutor/input_Teacher Allowance (RM)_allowance'), 
    '20.00')

WebUI.selectOptionByValue(findTestObject('Object Repository/Penjadwalan Kelas/Page_EMindTutor/select_Select Type                         _8fe307'), 
    'per_session', true)

WebUI.click(findTestObject('Object Repository/Penjadwalan Kelas/Page_EMindTutor/div_Monday                                 _308e77'))

WebUI.selectOptionByValue(findTestObject('Object Repository/Penjadwalan Kelas/Page_EMindTutor/select_Monday                              _769217'), 
    'Tuesday', true)

WebUI.setText(findTestObject('Object Repository/Penjadwalan Kelas/Page_EMindTutor/input_Schedule Day  Time_days0durasi_menit'), 
    '60')

WebUI.click(findTestObject('Object Repository/Penjadwalan Kelas/Page_EMindTutor/button_Approve  Assign'))

WebUI.closeBrowser()

