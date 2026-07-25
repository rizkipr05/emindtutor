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

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_Login - eMindTutor/input_Email_email'), 'superadmin@example.com')

WebUI.setEncryptedText(findTestObject('Object Repository/CRUD package/Page_Login - eMindTutor/input_Password_password'), 
    '8SQVv/p9jVTHLrggi8kCzw==')

WebUI.sendKeys(findTestObject('Object Repository/CRUD package/Page_Login - eMindTutor/input_Password_password'), Keys.chord(
        Keys.ENTER))

WebUI.click(findTestObject('Object Repository/CRUD package/Page_Dashboard  EMindTutor/a_Subscriptions'))

WebUI.click(findTestObject('Object Repository/CRUD package/Page_EMindTutor/a_Add Package'))

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__nama_pakej'), 'nama paket')

WebUI.click(findTestObject('Object Repository/CRUD package/Page_EMindTutor/p_Harga paket tidak berubah berdasarkan jum_a7015f'))

WebUI.click(findTestObject('Object Repository/CRUD package/Page_EMindTutor/h4_Harga Tetap'))

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__duration_days'), '30')

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__harga'), '200')

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__maks_pelajar'), '320')

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__maks_guru'), '50')

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__maks_kelas'), '300')

WebUI.click(findTestObject('Object Repository/CRUD package/Page_EMindTutor/button_Save Package'))

WebUI.click(findTestObject('Object Repository/CRUD package/Page_EMindTutor/a_Active_inline-flex items-center px-3 py-1_0b01a6'))

WebUI.click(findTestObject('Object Repository/CRUD package/Page_EMindTutor/a_Edit Package'))

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__nama_pakej'), 'resolusi baru')

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__duration_days'), '30')

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__harga_1'), '210')

WebUI.click(findTestObject('Object Repository/CRUD package/Page_EMindTutor/div_Package Type_relative w-11 h-6 bg-gray-_57d84e'))

WebUI.setText(findTestObject('Object Repository/CRUD package/Page_EMindTutor/input__maks_pelajar_1'), '330')

WebUI.click(findTestObject('Object Repository/CRUD package/Page_EMindTutor/button_Save Changes'))

WebUI.click(findTestObject('Object Repository/CRUD package/Page_EMindTutor/svg_Active_w-5 h-5'))

WebUI.acceptAlert()

WebUI.closeBrowser()

