# soaputils.py

import uuid
import base64
import os
from pathlib import Path
from zeep import Client, Settings
from zeep.transports import Transport
from requests import Session

class SoapUtils:
    def __init__(self, wsdl_path=None):
        if wsdl_path is None:
            wsdl_path = Path("C:/ArbiterSite/config/classes/rcc/rccservice.wsdl").as_uri()
        session = Session()
        transport = Transport(session=session, timeout=10)
        settings = Settings(strict=False, xml_huge_tree=True)
        self.client = Client(wsdl=wsdl_path, transport=transport, settings=settings)
        self.render_users_dir = Path("C:/cdn/thumbs/RenderedUsers")
        self.render_assets_dir = Path("C:/cdn/thumbs/RenderedAssets")
        self.render_users_dir.mkdir(parents=True, exist_ok=True)
        self.render_assets_dir.mkdir(parents=True, exist_ok=True)

    def generate_job_id(self):
        return str(uuid.uuid4())

    def open_job(self, script_text, expiration=60, category=0, cores=1.0):
        job_id = self.generate_job_id()

        job = {
            'id': job_id,
            'expirationInSeconds': float(expiration),
            'category': int(category),
            'cores': float(cores)
        }

        script = {
            'name': "MainScript",
            'script': script_text,
            'arguments': None
        }

        self.client.service.OpenJob(job=job, script=script)
        return job_id

    def execute(self, job_id, script_text, script_name="Script"):
        script = {
            'name': script_name,
            'script': script_text,
            'arguments': None
        }

        result = self.client.service.Execute(jobID=job_id, script=script)
        return result

    def close_job(self, job_id):
        self.client.service.CloseJob(jobID=job_id)

    def render_user(self, user_id):
        job_id = self.open_job("-- blank init for user render --")

        lua_script = f'''
            local userId = {user_id}
            local assetType = Enum.ThumbnailType.HeadShot
            local size = Enum.ThumbnailSize.Size352x352
            local thumbGen = game:GetService("ThumbnailGenerator")
            local success, image = pcall(function()
                return thumbGen:Click("PNG", 352, 352, true)
            end)
            if success then
                return image
            end
        '''

        result = self.execute(job_id, lua_script)
        self.close_job(job_id)

        if not result or not result[0]['value']:
            print(f"[!] Failed to render user {user_id}")
            return None

        b64_data = result[0]['value']
        output_path = self.render_users_dir / f"{user_id}.png"

        with open(output_path, "wb") as f:
            f.write(base64.b64decode(b64_data))

        print(f"[+] Rendered user {user_id} to {output_path}")
        return str(output_path)

    def render_asset(self, asset_id):
        job_id = self.open_job("-- blank init for asset render --")

        lua_script = f'''
            local assetId = {asset_id}
            local asset = game:GetService("InsertService"):LoadAsset(assetId)
            asset.Parent = game:GetService("Lighting")
            local thumbGen = game:GetService("ThumbnailGenerator")
            local success, image = pcall(function()
                return thumbGen:Click("PNG", 352, 352, true)
            end)
            if success then
                return image
            end
        '''

        result = self.execute(job_id, lua_script)
        self.close_job(job_id)

        if not result or not result[0]['value']:
            print(f"[!] Failed to render asset {asset_id}")
            return None

        b64_data = result[0]['value']
        output_path = self.render_assets_dir / f"{asset_id}.png"

        with open(output_path, "wb") as f:
            f.write(base64.b64decode(b64_data))

        print(f"[+] Rendered asset {asset_id} to {output_path}")
        return str(output_path)
